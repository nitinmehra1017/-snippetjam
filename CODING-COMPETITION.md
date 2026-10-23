# Coding Competition page

The child-theme template `templates/page-coding-competition.html` applies to the
WordPress page with slug `coding-competition`. It adds a separate editorial
competition experience without changing the homepage or Hackathon templates,
patterns, components or bundles. Nothing in this change activates a theme,
creates or edits a WordPress page, updates a database, changes a URL, deploys, or
modifies SEO output.

## WordPress ownership

The native Post Title block provides the H1. The native Post Content block renders
the existing page body once, with WordPress content filters, links and shortcodes
left to WordPress. Editors continue managing official page information under
Pages → Coding Competition. WordPress and any installed SEO plugin continue to
own document titles, canonical URLs, schema, robots and sitemaps.

The layout is composed through the `snippetjam/coding-competition-content`,
`snippetjam/coding-header` and `snippetjam/coding-footer` patterns. Supplemental
guidance lives in `inc/coding-competition.php` and is rendered as WordPress block
markup and custom HTML. It can be customized in the Site Editor template; it is
not stored or authored by React. Saved template/part overrides in WordPress can
supersede theme files and need checking in a private integration preview.

No confirmed competition rules were supplied. Read / Solve / Verify are explicitly
labeled as practice prompts, not an announced competition format. Eligibility,
entry format and permitted languages remain unconfirmed. FAQ answers state those
limits. No dates, fees, prizes, venue, scoring, round count, deadlines or entry
availability are invented. When official rules are available, update the pending
guidance and FAQ in the WordPress template alongside the page body so they remain
consistent. Preparation advice is labeled as suggestions rather than requirements.

Registration and other event links use the existing read-only permalink resolver.
Published page permalinks take precedence; missing/draft pages use the existing
slug fallback. The change does not create missing destination pages or imply
that registration is open.

Theme version `1.0.2` refreshes WordPress's versioned pattern discovery metadata
for the three new patterns. Ship the template, parts, patterns, PHP, theme header
and compiled assets together in a future separately approved release.

## Interaction and assets

Native `details`/`summary` elements provide the three practice prompts, FAQ and
mobile menu. They work without JavaScript. Browsers supporting the `name`
attribute keep the prompt disclosures exclusive; older browsers can open more
than one. All content and links remain in the server-rendered HTML.

React hydrates only the selected-prompt indicator. Labels come from WordPress's
rendered prompt headings; React does not maintain a separate content source.
Closing all prompts restores the neutral label. The existing island error
boundary restores server HTML after a rendering failure. Escape closes the
enhanced mobile menu and restores focus; following a menu link closes it.
There are no custom tabs, focus traps, routing, scroll interception or autoplay.
Reduced motion disables transitions. CSS is scoped to `.sj-coding` and its
conditional `snippetjam-coding` body class; native controls have visible focus
and at least 44px interaction targets in the tested fixture.

`functions.php` loads the new CSS/module only for
`is_page('coding-competition') && !is_front_page()`. The parent → child → event
stylesheet dependency and content-hash cache versions are retained. Missing
JavaScript leaves server content usable; missing CSS prevents module loading.

```sh
npm ci
npm run build
npm run dev:coding
```

The build now produces all three entries. `vite.coding.config.js` outputs
`dist/coding-competition/coding-competition.css` and `coding-competition.js`.
Like the Hackathon, the bundle is independent to preserve existing page bytes.
There is no standalone Vite HTML server. Build in a development checkout, never
in the active production theme: the homepage build clears `dist` before the
remaining entries rebuild. Commit all generated assets with source changes.

## Repeatable validation

QA tools and generated reports remain ignored; they do not alter the production
dependency graph or lockfile. Use a supported Node LTS, PHP CLI and Chromium.

```sh
npm install --prefix node_modules/.cache/coding-qa --no-audit --no-fund \
  --package-lock=false --save-exact @axe-core/playwright@4.10.2 \
  @wordpress/block-serialization-default-parser@5.35.0
npm run build
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/coding-competition/run.mjs
```

`PHP_BINARY` can point to a PHP wrapper/executable. Validation here used PHP
8.4.27, Node 24.19.0 and Chromium 154. The test renderer uses isolated WordPress
function doubles and a representative native page body. Browser requests are
intercepted locally; tests never request the production site or a database.
The JavaScript WordPress block parser checks the expanded native block structure.
PHP contracts check conditional enqueues, cache versions, published/draft links,
missing assets and page-class isolation. Existing production assets are compared
byte-for-byte with baseline commit `b299652`.

The browser suite checks 320, 360, 375, 390, 430, 600, 640, 768, 800, 801, 1024,
1440 and 1920px widths; overflow; minimum target sizes; all prompt selections;
Enter/Space and touch; focus; menu Escape/link closing; FAQ; reduced motion;
no JavaScript and blocked JavaScript; CMS content/link preservation; registration
permalinks; and existing Hackathon progress interactions. Axe WCAG A/AA checks and
screenshots run at 375 and 1440px. A 720px CSS viewport checks reflow equivalent
to a 1440px desktop at 200% zoom; this is not an actual browser zoom measurement.
Reports/screenshots are under `node_modules/.cache/coding-qa/results/`.

Validation result: all three production builds and compiled JavaScript syntax
checks passed; 17 PHP files passed syntax checks; 26 PHP architecture contracts
and 197 browser, accessibility, block-parser and regression assertions passed.
Axe reported no violations in the tested WCAG A/AA rule sets. The four existing
homepage/Hackathon CSS and JavaScript files remained byte-identical to baseline.

These checks do not replace private WordPress integration testing with actual
Twenty Twenty-Five global styles, Site Editor overrides, production page content,
SEO plugins and content filters. Screen-reader usability, browser diversity and
Core Web Vitals still need real integration review. The suite does not connect
to or alter production, and does not claim deployment readiness.
