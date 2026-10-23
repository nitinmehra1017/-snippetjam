import { spawnSync, execFileSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
function run(command, args, env = {}) {
  const diagnostics = command === 'node' ? { NODE_OPTIONS: `--require=${path.resolve('.github/agents/diagnostics.cjs')}` } : {};
  const result = spawnSync(command, args, { stdio: 'inherit', env: { ...process.env, ...env, ...diagnostics } });
  if (process.env.GITHUB_STEP_SUMMARY) fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY, `\n- ${command} ${args.join(' ')}: ${result.status === 0 ? 'passed' : 'failed'}\n`);
  if (result.status !== 0 && process.env.GITHUB_ACTIONS === 'true') console.log(`::error title=Failed CI phase::${command} ${args.join(' ')} exited ${result.status}`);
  if (result.error) throw result.error;
  if (result.status !== 0) process.exit(result.status || 1);
}
run('node', ['--test', 'tests/automation/policy.test.cjs']);
run('npm', ['run', 'build']);
// CI compares rebuilt assets with the candidate commit, rather than freezing old page designs.
run('git', ['diff', '--exit-code', '--', 'dist']);
if (execFileSync('git', ['ls-files', '--others', '--exclude-standard', '--', 'dist'], { encoding: 'utf8' }).trim()) throw new Error('Build generated uncommitted dist assets.');
for (const suite of ['coding-competition', 'robotics-challenge', 'workshops']) {
  if (!fs.existsSync(`tests/${suite}/run.mjs`)) throw new Error(`Required regression suite missing: ${suite}`);
  run('node', [`tests/${suite}/run.mjs`], { SNIPPETJAM_ASSET_BASELINE: 'HEAD' });
}
