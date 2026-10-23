const fs = require('node:fs');
// stdin is a JSON STRING produced by toJSON(), never shell source or a JSON object.
const raw = JSON.parse(fs.readFileSync(0, 'utf8'));
if (typeof raw !== 'string' || Buffer.byteLength(raw) > 900000) throw new Error('Invalid patch transport.');
fs.writeFileSync(process.argv[2], raw, { mode: 0o600 });
