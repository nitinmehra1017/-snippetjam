import assert from 'node:assert/strict';
import { execFileSync } from 'node:child_process';
import { mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

const root = fileURLToPath(new URL('../../', import.meta.url));
const requireQA = createRequire(path.join(root, 'node_modules/.cache/coding-qa/package.json'));
const { chromium } = requireQA('playwright-core');
const { default: AxeBuilder } = requireQA('@axe-core/playwright');
const { parse } = requireQA('@wordpress/block-serialization-default-parser');
const php = process.env.PHP_BINARY || 'php';
const artifacts = path.join(root, 'node_modules/.cache/coding-qa/results/workshops');
mkdirSync(artifacts, { recursive: true });
let checks = 0;
function check(condition, message) { assert.ok(condition, message); checks++; }
function equal(actual, expected, message) { assert.deepEqual(actual, expected, message); checks++; }
const render = (...args) => execFileSync(php, ['tests/workshops/render.php', 'workshops', ...args], { cwd: root, encoding: 'utf8' });
const phpFiles = execFileSync('rg', ['--files', '-g', '*.php', '-g', '!node_modules'], { cwd: root, encoding: 'utf8' }).trim().split('\n');
for (const file of phpFiles) execFileSync(php, ['-l', file], { cwd: root });
console.log(`${phpFiles.length} PHP files passed syntax checks.`);
console.log(execFileSync(php, ['tests/workshops/contracts.php'], { cwd: root, encoding: 'utf8' }).trim());
for (const asset of ['snippetjam', 'hackathon/hackathon', 'coding-competition/coding-competition', 'robotics-challenge/robotics-challenge', 'workshops/workshops']) {
    execFileSync(process.execPath, ['--check', 'dist/' + asset + '.js'], { cwd: root });
    if (!asset.startsWith('workshops/')) for (const ext of ['css', 'js']) {
        const baseline = execFileSync('git', ['show', `e0edf8a:dist/${asset}.${ext}`], { cwd: root });
        check(baseline.equals(readFileSync(path.join(root, 'dist', `${asset}.${ext}`))), `${asset}.${ext} matches baseline`);
    }
}
console.log('All five JavaScript entries passed syntax checks; eight existing assets match baseline.');
const raw = render('raw');
const flat = [];
function visit(blocks) { for (const block of blocks) { flat.push(block); visit(block.innerBlocks); } }
visit(parse(raw));
equal(flat.filter((b) => b.blockName === 'core/post-title').length, 1, 'one native Post Title');
equal(flat.find((b) => b.blockName === 'core/post-title').attrs.level, 1, 'native title is H1');
equal(flat.filter((b) => b.blockName === 'core/post-content').length, 1, 'one native Post Content');
check(!raw.includes('wp:pattern ') && !raw.includes('wp:template-part '), 'all patterns and parts resolve');

const errors = [];
const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_BINARY || '/usr/bin/chromium', headless: true, args: ['--no-sandbox'] });
async function fixture(context, { blocked = false, html = render(), home = false } = {}) {
    const page = await context.newPage();
    page.on('pageerror', (error) => errors.push(`${home ? 'home' : 'workshops'}: ${error.message}`));
    page.on('console', (message) => {
        if (message.type() === 'error' && !(blocked && message.text().includes('net::ERR_FAILED'))) errors.push(`${home ? 'home' : 'workshops'}: ${message.text()}`);
    });
    const entry = home ? 'snippetjam' : 'workshops/workshops';
    await page.route('https://snippetjam.test/**', async (route) => {
        const url = new URL(route.request().url());
        if (url.pathname === '/fixture/') return route.fulfill({ contentType: 'text/html', body: html });
        if ([`/${entry}.css`, `/${entry}.js`].includes(url.pathname)) {
            if (blocked && url.pathname.endsWith('.js')) return route.abort();
            return route.fulfill({ contentType: url.pathname.endsWith('.css') ? 'text/css' : 'text/javascript', body: readFileSync(path.join(root, 'dist', url.pathname.slice(1))) });
        }
        return route.abort();
    });
    await page.goto('https://snippetjam.test/fixture/');
    return page;
}
const trackNames = ['Observe', 'Experiment', 'Share'];
const trackIds = ['observe', 'experiment', 'share'];
async function verifySelection(page, index, enhanced = true) {
    check(await page.locator(`#sjw-track-${trackIds[index]}`).isChecked(), `${trackNames[index]} radio selected`);
    equal(await page.locator('.sjw-track-panel:visible').count(), 1, 'only selected panel is visible');
    check(await page.locator(`#sjw-panel-${trackIds[index]}`).isVisible(), `${trackNames[index]} content visible`);
    if (enhanced) {
        await page.waitForFunction((name) => document.querySelector('.sjw-current [aria-live]').textContent === name, trackNames[index]);
        checks++;
    }
}
try {
    const context = await browser.newContext();
    const page = await fixture(context);
    await page.waitForSelector('.sjw-next:not([hidden])');
    equal(await page.locator('h1').count(), 1, 'one rendered H1');
    equal(await page.locator('main').count(), 1, 'one main landmark');
    equal(await page.locator('[data-fixture-content]').count(), 1, 'original CMS body appears once');
    equal(await page.locator('.sjw-cms-content a').getAttribute('href'), '/existing-page/?source=wordpress', 'original CMS URL retained');
    equal(await page.locator('.sjw-register').getAttribute('href'), '/entry-information/', 'published registration permalink');
    const ids = await page.locator('[id]').evaluateAll((items) => items.map((el) => el.id));
    equal(ids.length, new Set(ids).size, 'unique IDs');
    for (const anchor of await page.locator('a[href^="#"]').evaluateAll((links) => links.map((a) => a.hash.slice(1)))) check(ids.includes(anchor), `anchor ${anchor} resolves`);
    for (const label of await page.locator('.sjw-track-option label').all()) check((await label.textContent()).includes('EXAMPLE'), 'selector is labeled as an example');

    const widths = [320, 360, 375, 390, 430, 600, 640, 768, 800, 801, 1024, 1440, 1920];
    for (const width of widths) {
        await page.setViewportSize({ width, height: 960 });
        await page.evaluate(() => document.querySelectorAll('.sjw-faq-list details').forEach((el) => { el.open = true; }));
        check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), `no overflow at ${width}px`);
        const small = await page.locator('.sj-workshops a, .sj-workshops button, .sj-workshops summary, .sjw-track-option label').evaluateAll((items) => items.filter((el) => {
            if (el.closest('details:not([open])') && el.tagName !== 'SUMMARY') return false;
            const r = el.getBoundingClientRect();
            return el.getClientRects().length && (r.width < 43.9 || r.height < 43.9);
        }).map((el) => el.textContent));
        equal(small, [], `44px pointer targets at ${width}px`);
        for (let i = 0; i < 3; i++) {
            await page.locator(`label[for="sjw-track-${trackIds[i]}"]`).click();
            await verifySelection(page, i);
            check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), `selected ${trackNames[i]} fits ${width}px`);
        }
        if (width <= 800) {
            const menu = page.locator('.sjw-menu');
            await menu.locator('summary').click();
            check(await menu.evaluate((el) => el.open), 'mobile menu opens');
            await page.keyboard.press('Escape');
            check(!await menu.evaluate((el) => el.open), 'Escape closes menu');
            check(await menu.locator('summary').evaluate((el) => el === document.activeElement), 'menu focus restored');
            await menu.locator('summary').click();
            await menu.locator('a[href="#sjw-tracks"]').click();
            check(!await menu.evaluate((el) => el.open), 'menu link closes menu');
        }
        if ([375, 1440].includes(width)) {
            await page.screenshot({ path: path.join(artifacts, `workshops-${width}.png`), fullPage: true });
            await page.locator('.sjw-hero').screenshot({ path: path.join(artifacts, `hero-${width}.png`) });
            await page.locator('.sjw-tracks').screenshot({ path: path.join(artifacts, `tracks-${width}.png`) });
            const axe = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21aa']).analyze();
            writeFileSync(path.join(artifacts, `axe-${width}.json`), JSON.stringify(axe, null, 2));
            equal(axe.violations.map((v) => ({ id: v.id, nodes: v.nodes.map((n) => n.target) })), [], `axe WCAG A/AA at ${width}px`);
        }
    }
    await page.setViewportSize({ width: 375, height: 812 });
    await page.locator('#sjw-track-observe').focus();
    await page.keyboard.press('Space');
    await verifySelection(page, 0);
    check(await page.locator('label[for="sjw-track-observe"]').evaluate((el) => getComputedStyle(el).outlineStyle !== 'none'), 'visible radio focus');
    await page.keyboard.press('ArrowRight'); await verifySelection(page, 1);
    await page.keyboard.press('ArrowDown'); await verifySelection(page, 2);
    await page.keyboard.press('ArrowRight'); await verifySelection(page, 0);
    await page.keyboard.press('ArrowLeft'); await verifySelection(page, 2);
    const next = page.locator('.sjw-next');
    await next.focus();
    for (const [key, index] of [['Enter', 0], ['Space', 1], ['Enter', 2]]) {
        await next.press(key); await verifySelection(page, index);
        check(await next.evaluate((el) => el === document.activeElement), 'next-example shortcut keeps button focus');
    }
    const faq = page.locator('.sjw-faq-list details').first();
    await faq.evaluate((el) => { el.open = false; });
    await faq.locator('summary').focus(); await faq.locator('summary').press('Space');
    check(await faq.evaluate((el) => el.open), 'native FAQ opens with Space');
    await page.emulateMedia({ reducedMotion: 'reduce' });
    equal(await page.locator('.sjw-option-arrow').first().evaluate((el) => getComputedStyle(el).transitionDuration), '0s', 'reduced motion disables transitions');
    await page.setViewportSize({ width: 720, height: 450 });
    check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), 'reflow equivalent to 200% desktop zoom');

    const touchContext = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true });
    const touch = await fixture(touchContext);
    for (let i = 0; i < 3; i++) { await touch.locator(`label[for="sjw-track-${trackIds[i]}"]`).tap(); await verifySelection(touch, i); }
    await touchContext.close();
    for (const mode of ['disabled', 'blocked']) {
        const fallbackContext = await browser.newContext({ javaScriptEnabled: mode !== 'disabled', viewport: { width: 375, height: 812 } });
        const fallback = await fixture(fallbackContext, { blocked: mode === 'blocked' });
        for (let i = 0; i < 3; i++) { await fallback.locator(`label[for="sjw-track-${trackIds[i]}"]`).click(); await verifySelection(fallback, i, false); }
        check(!await fallback.locator('.sjw-next').isVisible(), 'enhancement-only button stays hidden');
        await fallback.locator('.sjw-menu > summary').click();
        check(await fallback.locator('.sjw-menu a[href="#sjw-tracks"]').isVisible(), 'native fallback menu');
        await fallback.locator('.sjw-menu > summary').click();
        await fallback.locator('.sjw-faq-list summary').first().click();
        check(await fallback.locator('.sjw-faq-list details').first().evaluate((el) => el.open), 'native fallback FAQ');
        equal(await fallback.locator('.sjw-register').getAttribute('href'), '/entry-information/', 'fallback registration permalink');
        await fallbackContext.close();
    }

    // Additional homepage interaction regression using its unchanged component and production bundle.
    const { createServer } = await import('vite');
    const React = await import('react');
    const { renderToString } = await import('react-dom/server');
    const server = await createServer({ root, configFile: path.join(root, 'vite.config.js'), server: { middlewareMode: true, hmr: false }, appType: 'custom' });
    let homeHTML;
    try {
        const { EventIndex } = await server.ssrLoadModule('/src/components/EventIndex.jsx');
        const events = ['hackathon', 'coding-competition', 'robotics-challenge', 'workshops'].map((slug, index) => ({ slug, number: `0${index + 1}`, title: slug.replaceAll('-', ' '), url: `/${slug}/`, mode: 'EXAMPLE', description: `Fixture description ${index}` }));
        const markup = renderToString(React.createElement(EventIndex, { events }));
        homeHTML = `<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Homepage interaction fixture</title><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="/snippetjam.css"></head><body><main class="sj-home"><script id="snippetjam-homepage-data" type="application/json">${JSON.stringify({ events })}</script><div id="snippetjam-react-root">${markup}</div></main><script type="module" src="/snippetjam.js"></script></body></html>`;
    } finally { await server.close(); }
    const home = await fixture(context, { home: true, html: homeHTML });
    await home.waitForSelector('.sj-event-switch:not([hidden])');
    for (let i = 0; i < 4; i++) {
        await home.locator('.sj-event-switch').nth(i).click();
        equal(await home.locator('.sj-event-caption h3').textContent(), ['hackathon', 'coding competition', 'robotics challenge', 'workshops'][i], 'homepage event selection remains functional');
    }
    equal(errors, [], 'no browser or React errors');
    console.log(`${checks} Workshops browser/accessibility/block-parser/regression assertions passed across ${widths.length} widths.`);
    console.log(`Screenshots and axe reports: ${artifacts}`);
} finally { await browser.close(); }
