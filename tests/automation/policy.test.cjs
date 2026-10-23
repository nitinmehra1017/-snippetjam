const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const os = require('node:os');
const path = require('node:path');
const { execFileSync } = require('node:child_process');
const { approve, revalidate, parseResult, allowedPath, applyPatch, issueDigest } = require('../../.github/agents/policy.cjs');

function fixture(overrides = {}) {
  const issue = { number: 7, title: 'Improve event navigation', body: 'Preserve existing links and test keyboard behavior.', state: 'open', labels: [{ name: 'agent-task' }], ...overrides.issue };
  const context = { repo: { owner: 'nitinmehra1017', repo: '-snippetjam' }, actor: 'maintainer', eventName: 'issue_comment', runId: 123, runAttempt: 1,
    payload: { issue: structuredClone(issue), comment: { body: '/codex implement' } }, ...overrides.context };
  const github = { rest: { repos: {
    getCollaboratorPermissionLevel: async () => ({ data: { permission: overrides.role || 'write' } }),
    get: async () => ({ data: { default_branch: overrides.defaultBranch || 'main' } }),
    getBranch: async () => ({ data: { commit: { sha: overrides.sha || 'a'.repeat(40) } } })
  }, issues: { get: async () => ({ data: issue }) }, pulls: { list: () => {} } }, paginate: async () => overrides.pulls || [] };
  return { github, context, issue };
}

test('maintainer approval freezes issue content and an immutable default-branch commit', async () => {
  const f = fixture(); const task = await approve(f);
  assert.equal(task.branch, 'codex/issue-7-123-1');
  assert.equal(task.baseRef, 'main'); assert.equal(task.baseSha, 'a'.repeat(40));
  assert.equal(task.issueDigest, issueDigest(f.issue));
});
for (const role of ['read', 'triage', 'none']) test(`${role} cannot dispatch paid development`, async () => {
  await assert.rejects(approve(fixture({ role })), /Approval requires/);
});
for (const issue of [{ state: 'closed' }, { labels: [] }, { pull_request: {} }, { body: '' }]) test(`invalid issue ${JSON.stringify(issue)} is rejected`, async () => {
  await assert.rejects(approve(fixture({ issue })));
});
test('pull-request comments are not issue task approvals', async () => {
  const f = fixture(); f.context.payload.issue.pull_request = {};
  await assert.rejects(approve(f), /Not an issue/);
});
test('near-match command does not approve a task', async () => {
  const f = fixture(); f.context.payload.comment.body = '/codex implement && deploy';
  await assert.rejects(approve(f), /Not an issue/);
});
test('an issue edit after the approval event requires fresh approval', async () => {
  const f = fixture(); f.issue.body = 'New instructions';
  await assert.rejects(approve(f), /changed after approval/);
});
test('duplicate open agent PR blocks another generation', async () => {
  await assert.rejects(approve(fixture({ pulls: [{ body: '<!-- snippetjam-agent-task:7 -->' }] })), /already has an open/);
});
test('manual approval validates a numeric issue and never accepts an arbitrary checkout ref', async () => {
  const f = fixture(); f.context.eventName = 'workflow_dispatch'; f.context.payload.inputs = { issue_number: '7', dry_run: 'true', ref: 'untrusted-branch' };
  assert.equal((await approve(f)).baseRef, 'main');
  f.context.payload.inputs.issue_number = '../main';
  await assert.rejects(approve(f), /positive issue/);
});
test('withdrawn approval or changed task prevents publication', async () => {
  const f = fixture(); const task = await approve(f); f.issue.labels = [];
  await assert.rejects(revalidate({ ...f, task }), /approval was withdrawn/);
});
test('a moving base prevents publishing a previously tested patch', async () => {
  const f = fixture(); const task = await approve(f);
  const moved = fixture({ sha: 'b'.repeat(40) });
  await assert.rejects(revalidate({ ...moved, task }), /Base advanced/);
});
test('revoked maintainer permission prevents publication', async () => {
  const task = await approve(fixture());
  await assert.rejects(revalidate({ ...fixture({ role: 'read' }), task }), /Approval requires/);
});
test('a changed default branch requires fresh approval', async () => {
  const task = await approve(fixture());
  await assert.rejects(revalidate({ ...fixture({ defaultBranch: 'other' }), task }), /Default branch changed/);
});
test('issue text stays data, including shell syntax and GitHub expressions', async () => {
  const text = 'Keep literal $(touch /tmp/not-executed) and ${{ secrets.UNKNOWN }}';
  const task = await approve(fixture({ issue: { body: text } }));
  assert.equal(task.body, text);
});
test('patch transport rejects empty, malformed and oversized data', () => {
  for (const value of ['', '{}', JSON.stringify({ patch: '', summary: '' }), JSON.stringify({ patch: 'diff --git ' + 'x'.repeat(48000), summary: '' })]) assert.throws(() => parseResult(value));
  assert.equal(parseResult(JSON.stringify({ patch: 'diff --git a/a b/a\n', summary: 'Example' })).summary, 'Example');
});
test('frontend scope excludes workflow, dependency, secret, traversal, instruction and test changes', () => {
  for (const name of ['.github/workflows/ci.yml', 'package.json', 'package-lock.json', 'tests/test.js', 'src/.env', 'src/../AGENTS.md', 'src/AGENTS.md', 'src/AGENTS.override.md', 'assets/private.key', 'vite.config.js', '../functions.php']) assert.equal(allowedPath(name), false, name);
  for (const name of ['src/feature.jsx', 'inc/page.php', 'templates/page.html', 'assets/diagram.svg', 'dist/new.js', 'functions.php', 'style.css', 'FEATURE.md']) assert.equal(allowedPath(name), true, name);
});
test('real git patches apply; protected-file and symlink patches are refused', () => {
  const original = process.cwd(); const temp = fs.mkdtempSync(path.join(os.tmpdir(), 'snippetjam-policy-'));
  const git = (...args) => execFileSync('git', ['-c', 'core.hooksPath=/dev/null', ...args], { cwd: temp, encoding: 'utf8', stdio: ['ignore', 'pipe', 'pipe'] });
  try {
    git('init'); git('config', 'user.name', 'Policy test'); git('config', 'user.email', 'test@example.invalid');
    fs.mkdirSync(path.join(temp, 'src')); fs.writeFileSync(path.join(temp, 'src/page.js'), 'original\n');
    git('add', '.'); git('commit', '-m', 'fixture');
    fs.writeFileSync(path.join(temp, 'src/page.js'), 'updated\n');
    const patch = git('diff', '--binary'); git('reset', '--hard', 'HEAD');
    process.chdir(temp);
    assert.deepEqual(applyPatch(patch, path.join(temp, 'changes.patch')).names, ['src/page.js']);
    assert.equal(fs.readFileSync(path.join(temp, 'src/page.js'), 'utf8'), 'updated\n');
    git('reset', '--hard', 'HEAD');
    fs.mkdirSync(path.join(temp, '.github')); fs.writeFileSync(path.join(temp, '.github/unsafe.js'), 'unsafe\n');
    git('add', '.github'); const protectedPatch = git('diff', '--cached', '--binary'); git('reset', '--hard', 'HEAD');
    assert.throws(() => applyPatch(protectedPatch, path.join(temp, 'protected.patch')), /protected configuration/);
    git('reset', '--hard', 'HEAD');
    fs.symlinkSync('/etc/passwd', path.join(temp, 'src/link'));
    git('add', 'src/link'); const linkPatch = git('diff', '--cached', '--binary'); git('reset', '--hard', 'HEAD');
    assert.throws(() => applyPatch(linkPatch, path.join(temp, 'symlink.patch')), /regular, non-executable/);
  } finally { process.chdir(original); fs.rmSync(temp, { recursive: true, force: true }); }
});
test('hosted diagnostics record assertion locations without raw actual values', () => {
  const temp = fs.mkdtempSync(path.join(os.tmpdir(), 'snippetjam-diagnostics-'));
  const summary = path.join(temp, 'summary.md');
  try {
    assert.throws(() => execFileSync(process.execPath, ['--require', path.resolve('.github/agents/diagnostics.cjs'), '-e', "require('node:assert/strict').equal('PRIVATE_ACTUAL', 'expected', 'example assertion')"], {
      env: { ...process.env, GITHUB_STEP_SUMMARY: summary }, stdio: 'pipe'
    }));
    const text = fs.readFileSync(summary, 'utf8');
    assert.ok(text.includes('ERR_ASSERTION')); assert.ok(text.includes('example assertion'));
    assert.ok(!text.includes('PRIVATE_ACTUAL'));
  } finally { fs.rmSync(temp, { recursive: true, force: true }); }
});
