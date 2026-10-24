# Robotics Challenge

Developed from `codex/coding-competition-page` at commit
`b7c4e93baeaffdf4cf4c9db5471ac2775a1be9af` on the separate
`codex/robotics-challenge-page` branch. No merge, deployment, theme activation or
production content/database changes are part of this work.

## WordPress architecture and content

`templates/page-robotics-challenge.html` follows WordPress's page-slug template
hierarchy for `/robotics-challenge/`. It composes separate robotics header/footer
parts and the `snippetjam/robotics-challenge-content` pattern. That pattern renders
`inc/robotics-challenge.php`. The three new patterns use the theme's existing
discovery convention. Theme version `1.0.3` refreshes versioned pattern metadata;
the Coding Competition regression now accepts subsequent theme release versions.

The native Post Title block provides the H1. The native Post Content block renders
the existing page body once. WordPress retains content filters, shortcodes, links,
URLs, document titles, canonical metadata, robots, schema and sitemap handling.
There are no Rank Math or other SEO filters, custom head output or client routes.
Official information remains editable under Pages → Robotics Challenge. Native
WordPress block/custom-HTML pattern markup owns the supplemental guidance; React
does not author event content. The Site Editor can customize the template and
parts. Existing saved overrides may supersede these files and require review on
a private WordPress installation.

No official task, competition rules, judging, hardware requirements, eligibility,
team format, dates, fees, prizes or location were supplied. Sense / Decide / Move
are explicitly labeled learning concepts, not announced event stages. The rover
is an illustrative concept, not specified equipment. Preparation is advice, not
an entry or equipment checklist. Do not purchase hardware based on this artwork.
When rules are confirmed, update the WordPress page and any pending template/FAQ
guidance together. The page does not imply registration is open.

All registration, schedule, FAQ and other event links use the existing read-only
published-page permalink resolver. Missing/draft pages retain the existing slug
fallback; the code does not create pages or change permalinks.

## Illustration and progressive enhancement

`assets/robotics-schematic.svg` is a small local technical rover drawing with
sensor, controller and drive groups. It has no external resources or ID collisions
when used twice. Its decorative SVGs are hidden from assistive technology; the
equivalent learning concepts are in the section's visible headings and text.
CSS supplies the notebook geometry and connection diagram, with no gradients,
image library, framework, animation loop or new production dependencies.

Native `details`/`summary` controls provide concept exploration, FAQ and mobile
navigation without JavaScript. CSS `:has()` highlights the selected schematic
group. Browsers without `:has()` still show the complete drawing and all native
content; older browsers without exclusive named disclosures may open multiple
concepts. React hydrates only the current-focus label, derived from the existing
WordPress-rendered headings. All-closed returns the neutral system label. The
shared island boundary preserves the trusted server fallback on render failure.

The mobile menu closes on enhanced link activation and Escape; Escape restores
summary focus. All controls use native keyboard semantics, visible focus and
at least 44px targets in the tested fixture. Reduced motion disables transitions.
There is no autoplay, focus trap, scroll interception or fake telemetry.

CSS is confined to `.sj-robotics` and the conditional `snippetjam-robotics` body
class. `functions.php` only enqueues robotics assets when
`is_page('robotics-challenge') && !is_front_page()`. The parent → child → event
stylesheet dependency chain and SHA-256 asset versions remain intact. Missing
JavaScript retains readable server content; missing CSS prevents module loading.

## Build and development

```sh
npm ci
npm run build
npm run dev:robotics
```

The production build now produces all four independent entries. The new
`vite.robotics.config.js` emits `dist/robotics-challenge/robotics-challenge.css`
and `robotics-challenge.js`. Existing homepage, Hackathon and Coding Competition
entries are not shared/restructured, preserving their output bytes. No lockfile
or dependency version changes are needed. Commit built assets with source.

Build only in development: the initial homepage build clears `dist` before the
other entries rebuild. There is no standalone Vite HTML server; the watch command
rebuilds assets for refreshing a separate development WordPress installation.

## Repeatable validation

QA tools and reports are ignored under `node_modules/.cache/`, outside production
dependencies. The existing Coding Competition QA tool installation is reused:

```sh
npm install --prefix node_modules/.cache/coding-qa --no-audit --no-fund \
  --package-lock=false --save-exact @axe-core/playwright@4.10.2 \
  @wordpress/block-serialization-default-parser@5.35.0
npm run build
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/robotics-challenge/run.mjs
PHP_BINARY=php CHROMIUM_BINARY=/usr/bin/chromium node tests/coding-competition/run.mjs
```

The PHP CLI can be supplied by `PHP_BINARY`, including an executable wrapper.
Validation uses PHP 8.4.27, Node 24.19.0 and Chromium 154. The test renderer uses
isolated WordPress function doubles and representative native page content,
not a running WordPress installation. Test entry points reject non-CLI requests.
Requests are intercepted locally; no test contacts production or a database.

Robotics contracts cover all existing event enqueues, body-class isolation,
front-page precedence, content-hash versions, missing assets, published/draft
registration links, native title/content ownership and pattern release version.
The WordPress JavaScript block parser checks the expanded native block structure.
All six existing page CSS/JS outputs are compared byte-for-byte with the starting
Coding Competition commit. The existing Coding Competition/Hackathon browser
suite is also run as a regression check.

Browser checks cover 320, 360, 375, 390, 430, 600, 640, 768, 800, 801, 1024,
1440 and 1920px widths; overflow and touch targets; every selected concept and
its SVG highlight; Enter/Space and touch; visible focus; mobile navigation;
FAQ; reduced motion; disabled/blocked JavaScript; original CMS content/links;
and registration permalinks. Axe WCAG A/AA checks and visual screenshots run at
375 and 1440px. A 720px CSS viewport checks reflow equivalent to a 1440px desktop
at 200% zoom; it does not simulate actual browser zoom. Robotics reports and
screenshots are in `node_modules/.cache/coding-qa/results/robotics/`.

Validation results: all four production builds and compiled JavaScript syntax
checks passed; all 23 PHP files passed syntax checks; 27 robotics PHP contracts
and 277 robotics browser/accessibility/block-parser/regression assertions passed.
The existing suite also passed 26 PHP contracts and 197 browser assertions. Axe
reported no violations in the tested WCAG A/AA rules at 375 and 1440px. All six
existing production CSS/JavaScript files stayed byte-identical to the starting
commit. Desktop and mobile screenshots were visually reviewed.

## Remaining integration checks

Private WordPress integration is still needed with real Twenty Twenty-Five
global styles, Site Editor overrides, official content, content filters and Rank
Math output. The fixture tests do not prove those integrations, screen-reader
usability, other browser engines or Core Web Vitals. No production installation
was accessed or activated. Publish/release decisions remain separate from this
feature branch and its test evidence.
