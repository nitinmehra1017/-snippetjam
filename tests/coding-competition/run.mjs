import assert from 'node:assert/strict';
import { execFileSync } from 'node:child_process';
import { mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

// QA packages live outside the theme dependency graph. See CODING-COMPETITION.md.
const root = fileURLToPath(new URL('../../', import.meta.url));
const qa = path.join(root, 'node_modules/.cache/coding-qa');
const requireQA = createRequire(path.join(qa, 'package.json'));
const { chromium } = requireQA('playwright-core');
const { default: AxeBuilder } = requireQA('@axe-core/playwright');
const { parse } = requireQA('@wordpress/block-serialization-default-parser');
const php = process.env.PHP_BINARY || 'php';
const render = (event, ...args) => execFileSync(php, ['tests/coding-competition/render.php', event, ...args], { cwd: root, encoding: 'utf8' });
let checks = 0;
function check(condition, message) { assert.ok(condition, message); checks++; }
function equal(actual, expected, message) { assert.deepEqual(actual, expected, message); checks++; }
const artifacts = path.join(qa, 'results');
mkdirSync(artifacts, { recursive: true });

const phpFiles = execFileSync('rg', ['--files', '-g', '*.php', '-g', '!node_modules'], { cwd: root, encoding: 'utf8' }).trim().split('\n');
for (const file of phpFiles) execFileSync(php, ['-l', file], { cwd: root, stdio: 'pipe' });
console.log(`${phpFiles.length} PHP files passed syntax checks.`);
console.log(execFileSync(php, ['tests/coding-competition/contracts.php'], { cwd: root, encoding: 'utf8' }).trim());
for (const asset of ['snippetjam.js', 'hackathon/hackathon.js', 'coding-competition/coding-competition.js']) {
    execFileSync(process.execPath, ['--check', 'dist/' + asset], { cwd: root });
}
console.log('All three compiled JavaScript entries passed syntax checks.');

const blocks = parse(render('coding-competition', 'raw'));
const flat = [];
function visit(items) { for (const item of items) { flat.push(item); visit(item.innerBlocks); } }
visit(blocks);
equal(flat.filter((block) => block.blockName === 'core/post-title').length, 1, 'one parsed native title');
equal(flat.filter((block) => block.blockName === 'core/post-content').length, 1, 'one parsed native content block');
equal(flat.filter((block) => block.blockName === 'core/post-title')[0].attrs.level, 1, 'native H1');
check(!render('coding-competition', 'raw').includes('wp:pattern '), 'all template patterns resolve');
for (const asset of ['snippetjam.js', 'snippetjam.css', 'hackathon/hackathon.js', 'hackathon/hackathon.css']) {
    const previous = execFileSync('git', ['show', `b299652:dist/${asset}`], { cwd: root });
    check(previous.equals(readFileSync(path.join(root, 'dist', asset))), `${asset} byte-identical to baseline`);
}

const errors = [];
const browser = await chromium.launch({ executablePath: process.env.CHROMIUM_BINARY || '/usr/bin/chromium', headless: true, args: ['--no-sandbox'] });
async function fixture(context, event = 'coding-competition', options = {}) {
    const page = await context.newPage();
    page.on('pageerror', (error) => errors.push(error.message));
    page.on('console', (message) => {
        if (message.type() === 'error' && !(options.blockJS && message.text().includes('net::ERR_FAILED'))) errors.push(message.text());
    });
    await page.route('https://snippetjam.test/**', async (route) => {
        const url = new URL(route.request().url());
        if (url.pathname === '/fixture/') {
            return route.fulfill({ contentType: 'text/html', body: render(event) });
        }
        const assets = event === 'hackathon' ? 'hackathon/hackathon' : 'coding-competition/coding-competition';
        if (url.pathname === `/${assets}.css` || url.pathname === `/${assets}.js`) {
            if (options.blockJS && url.pathname.endsWith('.js')) return route.abort();
            return route.fulfill({ contentType: url.pathname.endsWith('.css') ? 'text/css' : 'text/javascript', body: readFileSync(path.join(root, 'dist', url.pathname.slice(1))) });
        }
        return route.abort(); // Tests never contact production or external services.
    });
    await page.goto('https://snippetjam.test/fixture/');
    return page;
}
try {
    const context = await browser.newContext();
    const page = await fixture(context);
    await page.waitForFunction(() => document.querySelector('.sjc-round-status').textContent.includes('01'));
    equal(await page.locator('h1').count(), 1, 'one rendered H1');
    equal(await page.locator('main').count(), 1, 'one main landmark');
    equal(await page.locator('[data-fixture-content]').count(), 1, 'original CMS content appears once');
    equal(await page.locator('.sjc-cms-content a').getAttribute('href'), '/existing-page/?source=wordpress', 'CMS URL is preserved');
    equal(await page.locator('.sjc-register').getAttribute('href'), '/entry-information/', 'registration uses published permalink');
    const ids = await page.locator('[id]').evaluateAll((elements) => elements.map((el) => el.id));
    equal(ids.length, new Set(ids).size, 'unique element IDs');
    for (const anchor of await page.locator('a[href^="#"]').evaluateAll((links) => links.map((a) => a.hash.slice(1)))) {
        check(ids.includes(anchor), `anchor ${anchor} resolves`);
    }

    const widths = [320, 360, 375, 390, 430, 600, 640, 768, 800, 801, 1024, 1440, 1920];
    for (const width of widths) {
        await page.setViewportSize({ width, height: 960 });
        await page.evaluate(() => { document.querySelectorAll('.sjc-faq-list details').forEach((detail) => { detail.open = true; }); });
        check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), `no overflow at ${width}px`);
        const badTargets = await page.locator('.sj-coding a, .sj-coding summary').evaluateAll((elements) => elements.filter((el) => {
            if (el.closest('details:not([open])') && el.tagName !== 'SUMMARY') return false;
            const rect = el.getBoundingClientRect();
            return el.getClientRects().length && (rect.height < 43.9 || rect.width < 43.9);
        }).map((el) => ({ text: el.textContent, height: el.getBoundingClientRect().height })));
        equal(badTargets, [], `44px targets at ${width}px`);
        for (const index of [0, 1, 2]) {
            const summary = page.locator('[data-coding-round] > summary').nth(index);
            if (!await summary.evaluate((el) => el.parentElement.open)) await summary.click();
            await page.waitForFunction((number) => document.querySelector('.sjc-status-number').textContent === number, String(index + 1).padStart(2, '0'));
            equal(await page.locator('[data-coding-round][open]').count(), 1, `exclusive prompt ${index + 1} at ${width}px`);
            check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), `open prompt fits at ${width}px`);
        }
        if (width <= 800) {
            const menu = page.locator('.sjc-menu');
            await menu.locator('summary').click();
            check(await menu.evaluate((el) => el.open), `menu opens at ${width}px`);
            await page.keyboard.press('Escape');
            check(!await menu.evaluate((el) => el.open), `Escape closes menu at ${width}px`);
            check(await menu.locator('summary').evaluate((el) => el === document.activeElement), 'Escape restores focus');
            await menu.locator('summary').click();
            await menu.locator('a[href="#sjc-rounds"]').click();
            check(!await menu.evaluate((el) => el.open), 'following an anchor closes menu');
        }
        if ([375, 1440].includes(width)) {
            await page.screenshot({ path: path.join(artifacts, `coding-${width}.png`), fullPage: true });
            await page.locator('.sjc-hero').screenshot({ path: path.join(artifacts, `hero-${width}.png`) });
            await page.locator('.sjc-registration').screenshot({ path: path.join(artifacts, `registration-${width}.png`) });
            const a11y = await new AxeBuilder({ page }).withTags(['wcag2a', 'wcag2aa', 'wcag21aa']).analyze();
            writeFileSync(path.join(artifacts, `axe-${width}.json`), JSON.stringify(a11y, null, 2));
            equal(a11y.violations.map((v) => ({ id: v.id, nodes: v.nodes.map((n) => n.target) })), [], `axe WCAG A/AA at ${width}px`);
        }
    }
    await page.setViewportSize({ width: 375, height: 812 });
    // Enter and Space exercise native summary semantics; focus must stay on the trigger.
    for (const key of ['Enter', 'Space']) {
        const summary = page.locator('[data-coding-round] > summary').nth(1);
        await summary.focus();
        if (await summary.evaluate((el) => el.parentElement.open)) await summary.press('Enter');
        await summary.press(key);
        await page.waitForFunction(() => document.querySelector('.sjc-status-number').textContent === '02');
        check(await summary.evaluate((el) => el === document.activeElement && el.parentElement.open), `${key} opens prompt without moving focus`);
        check(await summary.evaluate((el) => getComputedStyle(el).outlineStyle !== 'none'), 'visible keyboard focus');
    }
    await page.locator('[data-coding-round][open] > summary').press('Enter');
    await page.waitForFunction(() => document.querySelector('.sjc-round-status').textContent.includes('Read. Solve. Verify.'));
    checks++;
    const faq = page.locator('.sjc-faq-list details').first();
    await faq.evaluate((el) => { el.open = false; });
    await faq.locator('summary').focus();
    await faq.locator('summary').press('Space');
    check(await faq.evaluate((el) => el.open), 'keyboard opens FAQ');
    await page.emulateMedia({ reducedMotion: 'reduce' });
    equal(await page.locator('.sjc-plus').first().evaluate((el) => getComputedStyle(el).transitionDuration), '0s', 'reduced motion disables transitions');
    // 200% desktop zoom has the same CSS viewport reflow as half the physical width.
    await page.setViewportSize({ width: 720, height: 450 });
    check(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), 'reflow at equivalent 200% desktop zoom');

    const touchContext = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true });
    const touch = await fixture(touchContext);
    for (let i = 0; i < 3; i++) {
        const summary = touch.locator('[data-coding-round] > summary').nth(i);
        if (await summary.evaluate((el) => el.parentElement.open)) await summary.tap();
        await summary.tap();
        await touch.waitForFunction((n) => document.querySelector('.sjc-status-number').textContent === n, String(i + 1).padStart(2, '0'));
        checks++;
    }
    await touchContext.close();

    for (const mode of ['disabled', 'blocked']) {
        const fallbackContext = await browser.newContext({ javaScriptEnabled: mode !== 'disabled', viewport: { width: 375, height: 812 } });
        const fallback = await fixture(fallbackContext, 'coding-competition', { blockJS: mode === 'blocked' });
        await fallback.locator('[data-coding-round] > summary').nth(2).click();
        check(await fallback.locator('[data-coding-round]').nth(2).evaluate((el) => el.open), `native rounds with JS ${mode}`);
        check(await fallback.locator('[data-coding-round]').nth(2).locator('h3').isVisible(), `prompt content with JS ${mode}`);
        await fallback.locator('.sjc-menu > summary').click();
        check(await fallback.locator('.sjc-menu a[href="#sjc-rounds"]').isVisible(), `native menu with JS ${mode}`);
        await fallback.locator('.sjc-menu > summary').click();
        await fallback.locator('.sjc-faq-list summary').first().click();
        check(await fallback.locator('.sjc-faq-list details').first().evaluate((el) => el.open), `native FAQ with JS ${mode}`);
        equal(await fallback.locator('.sjc-register').getAttribute('href'), '/entry-information/', 'fallback registration link');
        await fallbackContext.close();
    }

    // Exercise existing Hackathon enhancement, in addition to its unchanged bundle checks.
    const hackathon = await fixture(context, 'hackathon');
    await hackathon.setViewportSize({ width: 375, height: 812 });
    await hackathon.waitForSelector('.sjh-progress[data-enhanced="true"]');
    for (let i = 0; i < 7; i++) {
        const step = hackathon.locator('[data-hackathon-step]').nth(i);
        if (!await step.evaluate((el) => el.open)) await step.locator('summary').click();
        const name = await step.locator('h3').textContent();
        await hackathon.waitForFunction((text) => document.querySelector('.sjh-progress-name').textContent === text, name);
        checks++;
    }
    check(await hackathon.evaluate(() => document.documentElement.scrollWidth <= innerWidth), 'Hackathon mobile reflow preserved');
    equal(errors, [], 'no browser or React errors');
    console.log(`${checks} browser, accessibility, block-parser and regression assertions passed across ${widths.length} widths.`);
    console.log(`Screenshots and axe reports: ${artifacts}`);
} finally {
    await browser.close();
}
