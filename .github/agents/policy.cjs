const crypto = require('node:crypto');
const fs = require('node:fs');
const path = require('node:path');
const { execFileSync } = require('node:child_process');

const writableRoles = new Set(['admin', 'maintain', 'write']);
const git = (...args) => execFileSync('git', ['-c', 'core.hooksPath=/dev/null', '-c', 'core.fsmonitor=false', ...args], { encoding: 'utf8' });
const digest = (text) => crypto.createHash('sha256').update(text).digest('hex');
const issueDigest = (issue) => digest(JSON.stringify({ title: issue.title, body: issue.body || '' }));

function allowedPath(filename) {
  const parts = filename.split('/');
  if (!filename || filename.includes('\\') || parts.some((p) => !p || p.startsWith('.')) || /(^|\/)AGENTS(?:\.override)?\.md$/i.test(filename)) return false;
  if (/\.(?:pem|key|p12|pfx)$/i.test(filename)) return false;
  return /^(?:src|inc|patterns|parts|templates|assets|dist)\//.test(filename)
    || ['functions.php', 'style.css'].includes(filename)
    || (/^[A-Za-z0-9_-]+\.md$/.test(filename) && filename !== 'AGENTS.md');
}

async function requireMaintainer(github, repo, username) {
  const { data } = await github.rest.repos.getCollaboratorPermissionLevel({ ...repo, username });
  if (!writableRoles.has(data.permission)) throw new Error('Approval requires repository write, maintain or admin permission.');
}

async function approve({ github, context }) {
  const repo = context.repo;
  await requireMaintainer(github, repo, context.actor);
  const manual = context.eventName === 'workflow_dispatch';
  if (!manual && (context.eventName !== 'issue_comment' || context.payload.issue?.pull_request || context.payload.comment?.body !== '/codex implement')) throw new Error('Not an issue task approval.');
  const rawNumber = manual ? context.payload.inputs.issue_number : context.payload.issue.number;
  if (!/^[1-9][0-9]*$/.test(String(rawNumber))) throw new Error('A positive issue number is required.');
  const number = Number(rawNumber);
  const { data: issue } = await github.rest.issues.get({ ...repo, issue_number: number });
  if (issue.pull_request || issue.state !== 'open' || !issue.labels.some((l) => l.name === 'agent-task')) throw new Error('Expected an open issue carrying agent-task.');
  if (!manual && issueDigest(issue) !== issueDigest(context.payload.issue)) throw new Error('Issue changed after approval; approve the updated task again.');
  if (!issue.body || issue.body.length > 16000) throw new Error('Task body must contain 1–16000 characters.');
  const pulls = await github.paginate(github.rest.pulls.list, { ...repo, state: 'open', per_page: 100 });
  if (pulls.some((pr) => (pr.body || '').includes(`<!-- snippetjam-agent-task:${number} -->`))) throw new Error('This issue already has an open agent PR.');
  const { data: repository } = await github.rest.repos.get(repo);
  const baseRef = repository.default_branch;
  const { data: branch } = await github.rest.repos.getBranch({ ...repo, branch: baseRef });
  return { issue: number, title: issue.title, body: issue.body, issueDigest: issueDigest(issue),
    baseRef, baseSha: branch.commit.sha, approver: context.actor,
    branch: `codex/issue-${number}-${context.runId}-${context.runAttempt || process.env.GITHUB_RUN_ATTEMPT || 1}` };
}

function parseResult(raw) {
  if (typeof raw !== 'string' || raw.length > 400000 || Buffer.byteLength(raw) > 900000) throw new Error('Missing or oversized Codex result.');
  const result = JSON.parse(raw);
  if (typeof result.patch !== 'string' || !result.patch.startsWith('diff --git ') || result.patch.length > 350000) throw new Error('Expected a nonempty git patch of at most 350000 characters.');
  if (typeof result.summary !== 'string' || result.summary.length > 2000) throw new Error('Invalid result summary.');
  return result;
}

function applyPatch(patch, patchFile) {
  fs.mkdirSync(path.dirname(patchFile), { recursive: true });
  fs.writeFileSync(patchFile, patch);
  git('apply', '--check', '--index', '--whitespace=error-all', patchFile);
  git('apply', '--index', '--whitespace=error-all', patchFile);
  const names = git('diff', '--cached', '--name-only', '-z').split('\0').filter(Boolean);
  if (!names.length || names.some((name) => !allowedPath(name))) throw new Error('Patch changes protected configuration, dependencies, tests, instructions, secrets, or paths outside frontend scope.');
  for (const record of git('ls-files', '--stage', '-z', '--', ...names).split('\0').filter(Boolean)) {
    if (!record.startsWith('100644 ')) throw new Error('Only regular, non-executable files are permitted.');
  }
  return { names, sha256: digest(patch) };
}

async function revalidate({ github, context, task }) {
  await requireMaintainer(github, context.repo, task.approver);
  const { data: repository } = await github.rest.repos.get(context.repo);
  if (repository.default_branch !== task.baseRef) throw new Error('Default branch changed; approve against the new default.');
  const { data: issue } = await github.rest.issues.get({ ...context.repo, issue_number: task.issue });
  if (issue.state !== 'open' || !issue.labels.some((l) => l.name === 'agent-task') || issueDigest(issue) !== task.issueDigest) throw new Error('Task changed or approval was withdrawn; approve again.');
  const { data: branch } = await github.rest.repos.getBranch({ ...context.repo, branch: task.baseRef });
  if (branch.commit.sha !== task.baseSha) throw new Error('Base advanced; rerun approval and test against the new base.');
}

module.exports = { approve, revalidate, parseResult, applyPatch, allowedPath, digest, issueDigest, git };
