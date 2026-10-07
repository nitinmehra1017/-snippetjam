# Snippet Jam homepage

This WordPress block child theme inherits Twenty Twenty-Five. It requires
WordPress 6.7+ and PHP 7.2+, matching the parent. The `codex-homepage` work is
uncommitted and must be previewed before activation.

## Architecture and content

`templates/front-page.html` composes dedicated homepage header/footer parts and a
main landmark. The `snippetjam/front-page-content` pattern renders
`inc/homepage.php` on the server. There is one primary heading: CODE. COMPETE.
CREATE. Other WordPress pages continue to inherit the parent templates.

`inc/homepage-content.php` reads published WordPress pages once per request. It
makes no database, option, URL, SEO, or server changes. Links use existing page
permalinks made relative; navigation also retains the supplied relative URLs.

The current front page (or published `home` page) supplies paragraphs under its
existing headings: Code. Compete. Create, About Snippet Jam, the four event names,
and Why Participate. The adapter reuses this copy in the new layout rather than
appending the old card layout and a second H1. An individual event page's published
excerpt/content takes precedence over its Home page summary. Keep those Home
headings when editing the source copy, or publish copy on the corresponding pages.

Schedule, registration, and venue/contact copy comes from their respective pages.
Empty pages use neutral pending details. No dates, times, rooms, fees, prizes,
contacts, eligibility rules, or deadlines are assumed. FAQ H2–H4 questions with
following answer content are read from `/faq/`, up to five; otherwise the homepage
uses concept-level answers and links to the existing pages. If the existing
reading setting is latest posts, the inherited WordPress query is retained below
the event experience.

## Progressive enhancement

All sections, headings, links, event rows, the initial event preview, and journey
steps exist in WordPress HTML before JavaScript. FAQ and mobile navigation use
native `details`/`summary` and work without JavaScript.

React hydrates only the event index and the journey indicator. Event links always
navigate to WordPress; separate preview buttons support touch/keyboard selection,
and hover/focus changes the diagram and description. Native journey buttons cover
each row: mouse hover temporarily previews a step, while focus, click, or tap selects
it. Scrolling resumes viewport activation and suppresses stationary-pointer hover
until the mouse moves. The journey observer and a passive scroll listener, limited
to one animation-frame update at a time, synchronize the step indicator/rail. A
small heading shift is limited to fine-pointer devices without reduced motion.
Each React
island has an error boundary that restores its trusted WordPress HTML if rendering
fails. Invalid JSON keeps the server-rendered event index.

Hero entrance and section-title reveals are small CSS effects. Reduced motion
disables all animation/transitions and skips reveal observers. Journey progress
still communicates the visible step. Escape closes the mobile menu and restores
focus; following a link also closes it. No animation, routing, or UI library is
added. The diagrams are a small local SVG; fonts are system fonts.

## Assets and builds

```sh
npm ci
npm run build
```

Commit `dist/snippetjam.css` and `dist/snippetjam.js` with source changes.
Production needs no Node process. `node_modules/` remains ignored.
`npm run dev` watches and rebuilds assets; refresh a development/staging WordPress
preview. There is no standalone Vite HTML server or HMR integration.

The stylesheet dependency chain is `twentytwentyfive-style` → `snippetjam-style`
→ `snippetjam-react`. Homepage CSS/JS is limited to `is_front_page()` and a scoped
body class. File-content SHA-256 hashes provide cache versions. WordPress's native
script-module API loads the Vite ES module. CSS remains available when JavaScript
is missing; JS is skipped if either built entry is missing/unreadable. Relative
asset URLs, one CSS bundle, and hashed supplementary assets remain configured.

## Validation and activation checklist

Production build, PHP syntax, compiled JavaScript syntax, WordPress block-parser
and module-output checks, DOM interaction/error recovery, motion guards, link
preservation, and palette contrast are checked without bootstrapping live WordPress.
Temporary DOM tooling/fixtures stay in ignored `node_modules/.cache/` and are not
production dependencies.

CSS has deliberate desktop, laptop/tablet, mobile, and small-phone layouts. Still
check actual browser rendering at 1920, 1440, 1024, 768, 640, 375, and 320 pixels;
200% zoom; font overflow; touch previews; keyboard focus; native accordion behavior;
reduced motion; and no-JavaScript/blocked assets. Test the real WordPress loop,
parent global styles, saved Site Editor overrides, SEO head output, cache versions,
and Core Web Vitals on staging. DOM tests cannot measure these browser behaviors.

Vite remains pinned to 5.4.14 and needs a separately validated security update.
Host Node 18 is EOL; use supported Node LTS for future tooling work. This workflow
exposes no Vite development server. No theme activation, server changes, database
changes, commits, or pushes are part of this implementation.
