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

        steps.forEach((step, index) => {
            const button = buttons[index];
            if (!button) return;
            button.disabled = false;
            const choose = () => {
                manualSelection = true;
                preview = null;
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

        listen(window, 'scroll', () => {
            if (window.scrollY === lastScrollY) return;
            lastScrollY = window.scrollY;
            manualSelection = false;
            preview = null;
            suppressHover = true; // A stationary pointer must not override scrolling rows.
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
