import { spawnSync, execFileSync } from 'node:child_process';
import fs from 'node:fs';
function run(command, args, env = {}) {
  const result = spawnSync(command, args, { stdio: 'inherit', env: { ...process.env, ...env } });
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
