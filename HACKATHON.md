# Hackathon page

Phase 2 is developed exclusively in the independent clone on
`codex/hackathon-redesign`. These changes have not been deployed.

## WordPress and content

`templates/page-hackathon.html` follows the block-theme page-slug hierarchy. It
applies automatically to the existing `/hackathon/` page; no page ID, template
assignment, database edit or permalink change is required.

`snippetjam/hackathon-content` renders the event presentation on the server.
The native Post Title block supplies the single H1. The native Post Content block
renders the complete existing page body exactly once in the overview. Editors
continue editing that body under Pages → Hackathon. Existing headings, links,
shortcodes and normal WordPress content filters remain available. No copy is
extracted, rewritten, duplicated or persisted back to the page.

The journey uses the seven participant stages already approved for the homepage.
Preparation copy consists of suggestions, not event logistics. Dates, durations,
fees, prizes, venues, eligibility, deadlines and team-size limits are not supplied.
Registration and event information remain marked as pending.

The event header/footer have their own parts and patterns. Their homepage section
links include the homepage URL; back-to-top targets the event main landmark.
`inc/event-links.php` resolves published page permalinks without writing anything.
The `/schedule-2/` link inside the existing post body is left intact. The event
navigation uses the existing Schedule page, just as homepage navigation does.

There are no SEO, canonical, robots, title, schema, Rank Math or sitemap filters.
WordPress's normal block-theme head and footer rendering remain in charge.

## Pattern discovery and release version

The child-theme version is `1.0.1`. Installed WordPress 7.1.3 caches pattern-file
metadata with the theme version. `WP_Theme::get_pattern_cache()` accepts a cached
list only when its version matches the current `style.css` Version header.
The release increment rejects the old `1.0.0` homepage-only list, causing core to
scan and register all four child patterns, including the three Hackathon patterns.
No custom registration hook or explicit cache-clearing code is necessary.

Increment the WordPress theme version again when changing the pattern-file list
or its cached metadata. The npm package version is unrelated to this mechanism.
Deploy the complete release, including `style.css`, pattern files and built assets,
together. Do not clear production caches during development.

The clone-only `node_modules/.cache/hackathon-qa/pattern-cache.php` regression test
loads installed WordPress discovery and registry code with in-memory transient
functions. It reproduces the stale old-version cache, verifies first-request
rediscovery and registration, resolves every Hackathon pattern reference, and
checks subsequent warm-cache and cold-cache requests. It does not bootstrap
WordPress or connect to a database; a private WordPress integration preview is
still needed before deployment.

## Assets and interaction

`functions.php` enqueues the event CSS and ES module only when
`is_page('hackathon') && !is_front_page()`. It retains the parent → child → page
stylesheet dependency chain and SHA-256 file-content cache versions. Missing
JavaScript leaves the styled, server-rendered page and native controls usable.

CSS is scoped to `.sj-hackathon` and the conditional event body class. The existing
homepage CSS, JavaScript, templates, content and components are unchanged.

Native details/summary elements provide the mobile menu and seven journey
accordions, including keyboard and touch behavior. React enhances only the
build-log number/progress meter. Its fallback remains a neutral seven-stage label.
Menu links close the enhanced menu; Escape closes it and restores summary focus.
There is no routing, scroll interception, continuous animation or extra UI library.
Reduced motion disables transitions and arrow movement.

```sh
npm ci
npm run build
npm run dev:hackathon
```

The first build preserves the original homepage entry. A separate Vite build emits
`dist/hackathon/hackathon.css` and `dist/hackathon/hackathon.js`, using the existing
pinned React/Vite dependencies. The second entry bundles React separately to keep
the deployed homepage entry byte-identical. Keep both page builds in Git alongside
source changes. Run builds in development/CI, never in the active production theme:
the default Vite build clears its output directory before rebuilding both entries.

## Verification and private preview

QA fixtures and tooling are ignored under `node_modules/.cache/hackathon-qa/`.
They render PHP presentation with isolated WordPress function stubs, parse the
native block structure, include representative WordPress flow-layout spacing,
and use Chromium request interception rather than a public
server. The fixture uses the Hackathon HTML captured in the read-only assessment.
No WordPress bootstrap, database connection or production request is needed.

The local checks cover conditional enqueues/cache versions, permalink resolution,
one Post Title/Post Content block, CSS scoping, responsive overflow, arrow inset,
touch-target size, all seven stages with mouse/keyboard/touch, menu behavior,
registration links, reduced motion and no-JavaScript fallback. Homepage assets are
compared byte-for-byte with HEAD and its existing interactions are regression-tested.

Screenshots are available in that QA directory. A temporary loopback-only fixture
preview could be added later for visual review; it would not constitute a full
WordPress preview. A separate private WordPress installation/database is the next
step for integration testing with actual parent global styles, Rank Math, content
filters and saved Site Editor customizations. That setup needs separate approval.
No production preview plugin or live theme switch is part of this work.

Latest clone validation: 33 PHP architecture contracts and 332 Chromium assertions
passed with no browser errors. Widths checked: 320, 360, 375, 390, 412, 430, 640,
768, 850, 851, 1024, 1440 and 1920 pixels. These fixtures do not replace a private
WordPress/Rank Math integration preview.
