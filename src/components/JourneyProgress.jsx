import { useEffect, useState } from 'react';

export function JourneyProgress() {
    const [active, setActive] = useState(0);
    useEffect(() => {
        const steps = Array.from(document.querySelectorAll('[data-journey-step]'));
        const list = document.querySelector('.sj-journey-list');
        if (!steps.length || !list) return;
        const buttons = steps.map((step) => step.querySelector('.sj-journey-trigger'));
        const hoverInput = window.matchMedia('(hover: hover) and (pointer: fine)');
        const removeListeners = [];
        let selected = 0;
        let preview = null;
        let displayed = null;
        let manualSelection = false;
        let selectionScrollGuard = false;
        let scrollSettleTimer = null;
        let suppressHover = false;
        let pointerPosition = null;
        let lastScrollY = window.scrollY;
        let frame = null;

        function show(index) {
            if (displayed === index) return;
            displayed = index;
            setActive(index);
            steps.forEach((step, stepIndex) => { step.dataset.active = stepIndex === index ? 'true' : 'false'; });
            list.style.setProperty('--sj-journey-progress', String((index + 1) / steps.length));
        }

        function select(index) {
            selected = index;
            buttons.forEach((button, stepIndex) => button?.setAttribute('aria-pressed', String(stepIndex === index)));
            show(preview ?? selected);
        }

        function selectFromViewport() {
            const bounds = list.getBoundingClientRect();
            if (bounds.bottom <= 0 || bounds.top >= window.innerHeight) return;
            const visible = steps.map((step, index) => ({ index, bounds: step.getBoundingClientRect() }))
                .filter(({ bounds: row }) => row.bottom > 0 && row.top < window.innerHeight);
            if (!visible.length) return;
            const anchor = window.innerHeight * 0.2;
            const nearest = visible.reduce((closest, row) =>
                Math.abs(row.bounds.top - anchor) < Math.abs(closest.bounds.top - anchor) ? row : closest);
            select(nearest.index);
        }

        function listen(target, name, callback, options) {
            target.addEventListener(name, callback, options);
            removeListeners.push(() => target.removeEventListener(name, callback, options));
        }

        function resumeScrollInput() {
            selectionScrollGuard = false;
            window.clearTimeout(scrollSettleTimer);
            scrollSettleTimer = null;
            // Keep the selection until this input actually moves the viewport.
        }

        function waitForSelectionScroll() {
            window.clearTimeout(scrollSettleTimer);
            scrollSettleTimer = window.setTimeout(() => {
                selectionScrollGuard = false;
                scrollSettleTimer = null;
                lastScrollY = window.scrollY;
                // Settling alone must not replace the explicitly selected step.
            }, 150);
        }

        steps.forEach((step, index) => {
            const button = buttons[index];
            if (!button) return;
            button.disabled = false;
            const choose = () => {
                manualSelection = true;
                selectionScrollGuard = true;
                preview = null;
                // Focus/click can reveal a row before its scroll event is delivered.
                lastScrollY = window.scrollY;
                if (frame !== null) window.cancelAnimationFrame(frame);
                frame = null;
                waitForSelectionScroll();
                select(index);
            };
            listen(button, 'focus', choose);
            listen(button, 'click', choose); // Native Enter/Space and touch taps use the same selection.
            listen(step, 'pointerenter', (event) => {
                if (!hoverInput.matches || event.pointerType !== 'mouse' || suppressHover) return;
                pointerPosition = [event.clientX, event.clientY];
                preview = index;
                show(index);
            });
            listen(step, 'pointermove', (event) => {
                if (!hoverInput.matches || event.pointerType !== 'mouse') return;
                const moved = !pointerPosition || event.clientX !== pointerPosition[0] || event.clientY !== pointerPosition[1];
                if (!moved) return;
                pointerPosition = [event.clientX, event.clientY];
                suppressHover = false;
                preview = index;
                show(index);
            });
            listen(step, 'pointerleave', () => {
                if (preview !== index) return;
                preview = null;
                show(selected);
            });
        });
        list.dataset.interactive = 'true';
        select(0);

        listen(window, 'wheel', (event) => {
            if (event.deltaY !== 0) resumeScrollInput();
        }, { passive: true });
        listen(window, 'touchmove', resumeScrollInput, { passive: true });
        listen(window, 'pointerdown', (event) => {
            if (!event.target.closest?.('.sj-journey-trigger')) resumeScrollInput();
        }, { passive: true });
        listen(window, 'resize', () => {
            if (!manualSelection) return;
            // Viewport changes can anchor-scroll without a new navigation gesture.
            selectionScrollGuard = true;
            lastScrollY = window.scrollY;
            waitForSelectionScroll();
        });
        listen(window, 'keydown', (event) => {
            if (event.defaultPrevented || event.target.isContentEditable ||
                event.target.closest?.('input, textarea, select, [role="textbox"], [role="slider"], [role="spinbutton"]')) return;
            if (!['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' '].includes(event.key)) return;
            if (event.key === ' ' && event.target.closest?.('button, summary, [role="button"]')) return;
            resumeScrollInput();
        });

        listen(window, 'scroll', () => {
            if (window.scrollY === lastScrollY) return;
            lastScrollY = window.scrollY;
            preview = null;
            suppressHover = true; // A stationary pointer must not override scrolling rows.
            if (manualSelection && selectionScrollGuard) {
                waitForSelectionScroll();
                show(selected);
                return;
            }
            manualSelection = false;
            if (frame !== null) return;
            frame = window.requestAnimationFrame(() => {
                frame = null;
                if (!manualSelection) selectFromViewport();
                show(preview ?? selected);
            });
        }, { passive: true });

        const observer = 'IntersectionObserver' in window ? new IntersectionObserver(() => {
            // Observer callbacks alone must not undo a click/focus selection.
            if (!manualSelection) selectFromViewport();
        }, { rootMargin: '-15% 0px -50% 0px', threshold: 0 }) : null;
        steps.forEach((step) => observer?.observe(step));
        return () => {
            observer?.disconnect();
            if (frame !== null) window.cancelAnimationFrame(frame);
            window.clearTimeout(scrollSettleTimer);
            removeListeners.forEach((remove) => remove());
            buttons.forEach((button) => {
                if (!button) return;
                button.disabled = true;
                button.removeAttribute('aria-pressed');
            });
            steps.forEach((step) => { delete step.dataset.active; });
            delete list.dataset.interactive;
            list.style.removeProperty('--sj-journey-progress');
        };
    }, []);

    return <div className="sj-journey-meter">
        <span className="sj-progress-number" aria-hidden="true">{String(active + 1).padStart(2, '0')}</span>
        <span className="sj-progress-label">OF 07<br />JOURNEY STEPS</span>
    </div>;
}
