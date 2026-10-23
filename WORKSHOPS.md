# Workshops learning journal

Developed on `codex/workshops-page` from `codex/robotics-challenge-page` at
`e0edf8a52858daecd979fa59d4926f4add68c2be`. No merge, deployment, theme activation,
production content edits or database updates are part of this change.

## Architecture and content ownership

`templates/page-workshops.html` follows WordPress's page-slug hierarchy for the
existing `/workshops/` page. It composes separate Workshops header/footer parts
and the `snippetjam/workshops-content` pattern, which renders `inc/workshops.php`.
The native Post Title block provides one H1. The native Post Content block renders
the complete existing page body once, retaining WordPress filters, shortcodes and
links. Official information stays under Pages → Workshops.

Supplemental copy, example tracks and FAQ are WordPress block/custom-HTML pattern
markup, not a React content source. They can be customized in the Site Editor's
Workshops template and parts. Existing saved overrides can supersede theme files
and need reviewing in a private WordPress installation. When official details are
published, update pending template guidance and FAQ together with the page body.

WordPress and Rank Math retain responsibility for URLs, titles, canonical metadata,
schema, robots and sitemaps. There are no custom SEO hooks, head output, client
routes or database writes. The existing read-only permalink resolver supplies
registration, schedule, FAQ and event URLs. Published pages take precedence;
missing/draft pages retain the existing slug fallback. No pages are created.

Observe, Experiment and Share are explicitly labeled illustrative learning tracks.
They describe learning approaches, not scheduled topics or promised sessions.
No workshop subjects, instructors, dates, schedules, fees, location, eligibility,
prerequisites, equipment requirements or registration availability are invented.
Preparation copy is advice, not an official equipment/software checklist.

Theme version `1.0.4` refreshes versioned pattern metadata for the three new
patterns. The Robotics regression now accepts later theme versions while retaining
its minimum pattern-release check. Existing page templates, PHP presentation,
components and styles are unchanged.

## Compositions and interaction

The page uses distinct compositions: an offset typographic poster hero; a reading
area with an indented WordPress body; an indexed example explorer and reading
sheet; full-width numbered preparation rows; a three-column eligibility ledger;
a large marginal Q with a focused FAQ; and a horizontal registration composition.
Mobile intentionally collapses the reading offset and ledger, changes preparation
rows, and turns the example index into three compact choices. Local inline SVGs
and CSS supply the poster and example illustrations. No fonts, image assets or
other resources are fetched externally. There are no gradients, UI frameworks,
generic card grids or continuous animations.

The explorer uses a labelled native radio group. Clicking/tapping a full-size
label selects a track; native Arrow keys/Space provide keyboard selection.
CSS `:has()` shows the corresponding panel without JavaScript. All copy exists
in server HTML. Browsers without `:has()` show all examples in readable order.
Missing CSS also leaves the content readable. Panels are articles, not a custom
tab widget; hidden panels leave the accessibility tree through `display:none`.

React hydrates only the live current-example label and optional Next example
shortcut. It reads labels from WordPress-rendered HTML rather than maintaining
duplicate copy. Next example wraps through native inputs and keeps focus on its
button for repeated keyboard use. The button stays hidden without enhancement.
The existing island boundary retains trusted server fallback on a rendering error.

FAQ and mobile navigation use native details/summary. Enhanced menu links close
the menu, and Escape closes it and restores focus. Labels, controls and links
have visible focus and at least 44px targets in tested fixtures. Reduced motion
disables transitions. No focus trap, scroll interception or autoplay is added.

## Assets and development

`functions.php` only enqueues the new assets for
`is_page('workshops') && !is_front_page()`. CSS stays within `.sj-workshops` and
the conditional `snippetjam-workshops` body class. Existing parent → child → event
stylesheet ordering and SHA-256 cache versions remain intact. Missing JavaScript
retains native controls and content; missing CSS prevents module loading.

```sh
npm ci
npm run build
npm run dev:workshops
```

`vite.workshops.config.js` emits `dist/workshops/workshops.css` and `workshops.js`.
The complete build now produces five independent entries. The eight existing
homepage/event asset files remain byte-identical to the starting commit. No
production dependency or lockfile changes are required. Build only in development:
the first build clears `dist` before the other entries rebuild. Commit all built
assets alongside source. There is no standalone Vite HTML preview or HMR server;
watch commands rebuild assets for a separate WordPress development installation.

## Reproducible checks

Use supported Node LTS, a PHP CLI and Chromium. QA dependencies and generated
reports remain ignored under `node_modules/.cache/`, outside production packages:

```sh
npm install --prefix node_modules/.cache/coding-qa --no-audit --no-fund \
  --package-lock=false --save-exact @axe-core/playwright@4.10.2 \
  @wordpress/block-serialization-default-parser@5.35.0
npm run build
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/workshops/run.mjs
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/coding-competition/run.mjs
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/robotics-challenge/run.mjs
```

`PHP_BINARY` accepts an executable or wrapper. This environment uses Node 24.19.0,
PHP 8.4.27 and Chromium 154. PHP fixtures use isolated WordPress function doubles;
they do not bootstrap WordPress or connect to a database. Test PHP entry points
reject non-CLI access. Browser requests are intercepted locally, with no production
requests. The homepage interaction fixture server-renders its existing React
component through Vite's SSR transform and loads its unchanged production bundle.

Checks cover all production builds and JavaScript entries, PHP syntax, conditional
asset/body-class isolation, front-page precedence, published/draft registration
links, content hashes, missing assets, versioned pattern metadata and native title/
content ownership. The WordPress JavaScript block parser checks expanded blocks.
All eight existing CSS/JS files are compared with baseline `e0edf8a`.

Browser coverage includes 320, 360, 375, 390, 430, 600, 640, 768, 800, 801, 1024,
1440 and 1920px widths; all selected panels; overflow and 44px targets; radio
Arrow keys/Space; Enter/Space on Next example with focus retention; touch; menu
Escape/link closing; FAQ; original CMS content/links; reduced motion; disabled and
blocked JavaScript; and homepage event selection. Existing Coding Competition,
Hackathon and Robotics suites are rerun. Axe WCAG A/AA checks and visual screenshots
run at 375 and 1440px. A 720px CSS viewport checks reflow equivalent to a 1440px
desktop at 200% zoom; actual browser zoom is not simulated. Reports/screenshots:
`node_modules/.cache/coding-qa/results/workshops/`.

Validation results: all five production builds and compiled JavaScript syntax
checks passed; all 29 PHP files passed syntax checks. Workshops passed 30 PHP
contracts and 377 browser/accessibility/block-parser/regression assertions. The
existing suites passed another 53 PHP contracts and 474 browser assertions.
Axe reported no violations in the tested WCAG A/AA rules at 375 and 1440px.
All eight existing production CSS/JS files remained byte-identical to baseline.
Desktop and mobile screenshots were visually reviewed, including the corrected
mobile poster typography. The added homepage fixture explicitly declares UTF-8
to match its server-rendered text with the unchanged production enhancement.

## Remaining integration checks

Private WordPress integration remains necessary with actual Twenty Twenty-Five
global styles, Site Editor overrides, published content, content filters and Rank
Math metadata/schema output. Fixture tests do not establish those integrations,
screen-reader usability, browser-engine diversity or Core Web Vitals. Official
workshop programme and entry information must still come from the organizers.
The feature branch is for review; no production activation or deployment occurred.
