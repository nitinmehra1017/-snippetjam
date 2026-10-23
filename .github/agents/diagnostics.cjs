const fs = require('node:fs');
// Keep hosted failures diagnosable without copying command output or environment values.
process.on('uncaughtExceptionMonitor', (error) => {
  if (!process.env.GITHUB_STEP_SUMMARY) return;
  const locations = [...String(error.stack || '').matchAll(/tests\/([A-Za-z0-9_./-]+:\d+:\d+)/g)].map((match) => match[1]);
  const category = /^[A-Z0-9_]+$/.test(error.code || '') ? error.code : 'UNCAUGHT_ERROR';
  const assertion = error.code === 'ERR_ASSERTION' ? String(error.message).split('\n')[0].replace(/[^A-Za-z0-9 .:/_()=-]/g, '').slice(0, 180) : '';
  fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY, `\nFailure category: ${category}\n\n${assertion}\n\nTest locations: ${[...new Set(locations)].join(', ') || 'not available'}\n`);
});
