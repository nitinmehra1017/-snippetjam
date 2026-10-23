# Snippet Jam Frontend Instructions

## Project

Snippet Jam is a university technology-event website built with:

- WordPress
- Twenty Twenty-Five parent theme
- Snippet Jam child theme
- React
- Vite
- Custom CSS
- Azure VPS
- Nginx

WordPress must remain the CMS and SEO foundation.

Do not modify WordPress core or the Twenty Twenty-Five parent theme.

## Design Goal

The website should feel intentionally designed, experimental and editorial.

Visual references:
- developer portfolios
- technology festivals
- editorial event websites
- student technology culture

It must NOT look like a generic AI-generated SaaS landing page.

## Hard Rules

DO NOT use Tailwind.

DO NOT use gradients.

DO NOT use Bootstrap.

DO NOT use glassmorphism.

DO NOT use generic purple/blue AI styling.

DO NOT center every section.

DO NOT turn every section into rounded cards.

DO NOT use excessive border radius.

DO NOT use excessive shadows or glowing effects.

DO NOT use the common pattern:

centered headline
+ subtitle
+ two buttons
+ three cards.

Prefer custom CSS and CSS Grid.

## Visual Direction

Use:

- near-black
- warm off-white
- strong yellow accent

Prefer sharp geometry.

Use large expressive typography.

Use asymmetric layouts.

Use strong spacing and typography instead of decorative effects.

Some sections may use near-full viewport height.

## Hero

The hero must NOT be centered.

Prefer an asymmetric editorial composition.

Large typography such as:

CODE.
COMPETE.
CREATE.

Combine this with event information, navigation or imagery positioned
elsewhere in the composition.

## Events

Do not use four generic cards.

Prefer an interactive event index such as:

01 — HACKATHON
02 — CODING COMPETITION
03 — ROBOTICS CHALLENGE
04 — WORKSHOPS

Selecting or hovering an event may reveal additional imagery/content.

## Journey

Create an interactive chronological experience:

01 Keyword Research
02 SERP Analysis
03 Competitor Analysis
04 WordPress Development
05 Azure Deployment
06 Technical SEO
07 Performance Testing

Use purposeful scroll interaction to communicate progress.

## Motion

Motion must have purpose.

Good examples:

- scroll reveals
- timeline progression
- navigation state changes
- event image/content changes
- subtle typography movement
- responsive micro-interactions

Avoid:

- constant floating objects
- bouncing icons
- excessive parallax
- animation on every element

Respect prefers-reduced-motion.

## React

Use React for interactive UI.

Keep components small and understandable.

Do not unnecessarily convert WordPress functionality into React.

WordPress remains responsible for:

- content
- pages
- SEO
- Rank Math
- URLs
- sitemap
- schema

React is responsible for enhanced presentation and interaction.

## WordPress Safety

Never modify:

wp-admin/
wp-includes/
WordPress core files
Twenty Twenty-Five parent-theme files

Custom code belongs in snippetjam-child.

Never place credentials, database passwords, API secrets or private keys
in source code.

## Performance

Keep JavaScript lightweight.

Avoid unnecessary libraries.

Optimize images.

Lazy-load appropriate media.

Preserve strong Core Web Vitals.

Do not sacrifice performance for animation.

## Responsive Design

Mobile must be intentionally designed.

Do not simply shrink the desktop layout.

Maintain readable typography and approximately 44px minimum interactive
touch targets.

## Final Design Test

Before finishing any section ask:

"Could this have come from a generic AI landing-page prompt?"

If yes, redesign it.

The final result should feel authored rather than generated.

## GitHub collaboration

Approved issue-driven work follows `.github/AGENT_COLLABORATION.md`. Use a new
feature branch, preserve unrelated pages, commit rebuilt assets, and run the
existing regression suites. Never merge into the default branch or deploy as
part of an agent task. A maintainer reviews the PR after successful required
checks. Credentials belong only in secure environment/GitHub settings, never
in issue text, source, logs or comments. Changes to automation, tests,
dependencies or these instructions require a separate maintainer-reviewed task.
