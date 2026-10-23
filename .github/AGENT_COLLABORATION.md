# GitHub issues → approved Codex task → tested pull request

This configuration uses the official [OpenAI Codex Action](https://github.com/openai/codex-action)
(v1.13, pinned to its dereferenced release commit) and Codex CLI 0.164.0. It runs
on disposable GitHub-hosted Ubuntu runners. This is an API-billed Actions workflow,
not a call to an existing ChatGPT cloud task or an assumption that a ChatGPT
subscription provides an Actions API key.

## Activation and one-time authorization

1. Review this branch and get the configuration onto the repository's **default
   branch**, currently `main`. This branch starts from `codex/workshops-page`;
   `main` was still at `b299652` when inspected. The unmerged event-page chain and
   its regression suites must also be reviewed/integrated before activating this
   configuration. No branch was merged by this setup. Issue-comment and manual
   workflow triggers are not activated merely by pushing a feature branch.
2. Enable GitHub Actions and allow the pinned `actions/*`, local preparation
   action, and `openai/codex-action` in repository/organization Actions policy.
   Under Actions → General → Workflow permissions, allow GitHub Actions to create
   pull requests. Jobs request their individual permissions; no personal GitHub
   token is stored by this configuration.
3. The **Install agent-task label** workflow attempts to create `agent-task` on
   the setup branch's push. It preserves an existing label. If that run is
   unavailable/blocked, after activation run it from Actions → Install agent-task
   label → Run workflow. Alternatively create the label under Issues → Labels:
   name `agent-task`, colour `E4E85C`, description from `.github/labels.json`.
4. Create/reuse the GitHub environment `codex-tasks`, and restrict its deployment
   branches to the default branch (`main` currently). This environment gates the
   API credential; it does not deploy the website. Add/reuse **CODEX_OPENAI_API_KEY**
   there as an environment secret, using an OpenAI API project key with a suitable
   budget. Enter the value only in GitHub's secret UI; never in an issue, prompt,
   commit or chat. Optional environment reviewer restrictions can add another
   approval gate; they will require interaction on each dispatch. The generation
   job receives this secret only through the official action's secure proxy input.
5. Run **SnippetJam CI** and confirm the `SnippetJam checks` job succeeds. Then
   create/update an active ruleset for the default branch in Settings → Rules →
   Rulesets. Require a PR, at least one approving review, **SnippetJam checks**
   before merge, and branches up to date. Block force pushes and deletion; do not
   bypass the rule for the automation identity. Preserve existing stronger rules.
   Check names become selectable after their first hosted run. These protections
   require repository administration access and are not installed by YAML alone.
6. Add repository Actions variable **CODEX_TASKS_ENABLED=true** only when the
   prerequisites are ready. Without it, dispatch is disabled. Create a small test
   issue, select it in Actions → Approved Codex task → Run workflow, and leave
   **dry_run=true**. This tests live approval without an API call or PR. Then
   perform one small real task and verify the issue response, PR, and its head-SHA
   CI. Only that establishes end-to-end automation in your repository.

## Assigning and approving work

ChatGPT needs an explicitly authorized GitHub integration with issue-write access
to create/update issues itself. This chat currently exposes no GitHub issue-write
connector, and its GitHub API traffic is blocked by the environment proxy. This
configuration does not give an ordinary ChatGPT conversation a GitHub connection
or add a webhook that polls chat. Until a capable integration is connected, a
maintainer can submit the task through the **Agent development task** issue form.

Write the goal, scope, preservation requirements and observable acceptance
criteria. Do not put credentials in the issue. The form adds `agent-task`, which
classifies the task and **does not by itself authorize an API call**.

A repository user with write, maintain or admin permission approves the current
issue by posting exactly:

```text
/codex implement
```

Approval is an issue comment, not a comment on a PR. Read/triage users, unlabeled
or closed issues, near-match commands and issue edits do not dispatch. Automated
bot approval is not enabled. A fresh approval is required if the issue changes.
Alternatively, a maintainer can manually dispatch the issue with dry-run disabled.
Actor permissions are checked against GitHub's API; author association is not
treated as permission. Reactions and label changes are not approval mechanisms.

## Response and implementation mechanism

1. **approve** verifies the maintainer, label and open issue. It freezes title/body
   and an immutable default-branch SHA, checks for an existing open agent PR, and
   chooses `codex/issue-<issue>-<run>-<attempt>`. All issue text is data passed via
   JSON/environment values, never interpolated into shell code.
2. **generate** checks out that SHA without retained Git credentials, prepares
   dependencies, then invokes Codex with `:workspace` and `drop-sudo`. Codex is
   the last step on its runner and has only read GitHub permissions. Its final
   output is a structured patch, not a request to merge or deploy.
3. **validate**, on a new runner with no API key or GitHub write credentials,
   applies the patch, validates its paths/modes, creates a local candidate commit,
   rebuilds and runs all mandatory suites. Only success uploads a short-lived
   validated patch artifact and its digest.
4. **publish**, on another fresh runner, rechecks issue approval and the base SHA,
   verifies the patch digest, reapplies the tested patch, commits and pushes a new
   feature branch, and opens a PR linking the issue and validation run. It does
   not execute the proposed application code with its write token. It explicitly
   dispatches `ci.yml` on the published branch: `GITHUB_TOKEN`-created pushes/PRs
   do not otherwise start another push/PR workflow. That head-commit CI is the
   required merge check; the earlier task run alone is not the PR head's check.
5. **respond** posts a fixed status/run link on the original issue. A completed
   publication response links the PR and says head CI was requested, not that it
   has already passed. Failures link the run, and dry runs explicitly say Codex
   was not invoked. Raw prompts, Codex output and secret values are not posted.

Task runs are serialized per issue. A second approval refuses to generate if an
agent PR is already open. If the issue is closed, its label removed, its body/title
edited, the approver loses write permission, or the base advances, publication is
refused and a new approval is necessary. A failure after push/PR creation can leave
a branch/PR; inspect the run first. If only head-CI dispatch failed, manually run
SnippetJam CI on that branch instead of starting another implementation.

## Scope and checks

Automatic tasks are intentionally limited to frontend application paths: `src`,
`inc`, `patterns`, `parts`, `templates`, `assets`, `dist`, `functions.php`,
`style.css`, and root Markdown documentation other than `AGENTS.md`. They cannot
change workflows, tests, instructions, dependencies, build configs, hidden files,
secret/key files, executable modes, symlinks or submodules. Broader tasks require
a separate maintainer-reviewed coding task. Patch transport is limited to 350,000
characters; split larger tasks. A task body is limited to 16,000 characters.

The CI entry point is `node .github/agents/ci.mjs`. It tests workflow policy,
builds all five production entries, checks committed/dist reproducibility including
untracked generated files, then runs Coding, Robotics and Workshops suites. Those
cover PHP/compiled-JS syntax, WordPress block parsing, responsive layouts at 13
widths from 320–1920px, Axe checks, keyboard/touch, no-JavaScript behavior,
permalinks and existing event interactions, including the homepage fixture.

The existing suites still default to their historical asset snapshots when run
manually. CI sets `SNIPPETJAM_ASSET_BASELINE=HEAD` after a candidate commit, so a
legitimate page change is tested against its newly committed assets. The separate
rebuild/diff check prevents stale or missing bundles. Semantic regression tests
remain mandatory; assertions are not disabled to allow a changed design.

Local validation, with prepared dependencies, PHP and Chromium:

```sh
node --test tests/automation/policy.test.cjs
actionlint .github/workflows/*.yml
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node .github/agents/ci.mjs
```

The repository is a child theme, not a standalone WordPress server. These fixtures
do not establish live WordPress/Rank Math integration, screen-reader usability,
all browser engines or production Core Web Vitals. No production credentials,
deployment workflow, merge API call, auto-merge setting, or unattended deployment
is added. A human reviews the PR and merges only after required checks pass.

## Setup verification status

Local policy tests, workflow lint and application regression results are reported
in the setup conversation: 23 policy/diagnostic/transport tests, all three workflows passing actionlint,
five production builds, 29 PHP syntax checks, 83 PHP contracts and 851 browser/
accessibility/block-parser/regression assertions passed locally. No application
source or compiled page assets changed. The first push successfully created the
live `agent-task` label, confirmed on GitHub's label page and its successful
bootstrap run. Hosted CI initially failed and is being diagnosed separately;
passing local checks alone does not establish a passing hosted workflow.
A successful Git push is not proof of a hosted Actions
run. This environment's GitHub API proxy returned HTTP 403 even for read-only
metadata requests, so API label creation, Actions settings, secrets, rulesets,
dispatch, hosted statuses and PR creation cannot be asserted from those requests.
Inspect the label-bootstrap/CI runs after push and complete the one-time activation
above. The paid Codex issue-to-PR loop remains unverified until a real live task
has passed; no API secret was supplied or invented during setup.
