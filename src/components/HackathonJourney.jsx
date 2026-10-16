import { useEffect, useState } from 'react';

// Native details own the interaction and content. React only enhances the build-log meter.
export function HackathonJourney({ steps }) {
    const [active, setActive] = useState(0);
    const [enhanced, setEnhanced] = useState(false);

    useEffect(() => {
        const journey = document.querySelector('.sj-hackathon .sjh-journey-list');
        if (!journey) return;
        setEnhanced(true);
        const sync = (event) => {
            const detail = event?.target;
            if (detail?.matches('details[data-hackathon-step]') && detail.open) {
                const index = Number(detail.dataset.hackathonStep);
                if (Number.isInteger(index) && index >= 0 && index < steps.length) setActive(index);
            }
        };
        const openStep = journey.querySelector('details[open]');
        if (openStep) sync({ target: openStep });
        journey.addEventListener('toggle', sync, true);
        return () => journey.removeEventListener('toggle', sync, true);
    }, [steps]);

    return (
        <div className="sjh-progress" data-enhanced={enhanced ? 'true' : 'false'} style={{ '--sjh-progress': enhanced ? (active + 1) / steps.length : 1 }}>
            <div className="sjh-progress-count">
                <span aria-hidden="true">{String(enhanced ? active + 1 : steps.length).padStart(2, '0')}</span>
                <p><span className="sjh-eyebrow">{enhanced ? 'OF ' + String(steps.length).padStart(2, '0') + ' / JOURNEY STEPS' : 'SEVEN STAGES / YOUR JOURNEY'}</span>
                    <span className="sjh-progress-name" aria-live="polite" aria-atomic="true">{enhanced ? steps[active] : 'From idea to demo'}</span></p>
            </div>
            <div className="sjh-progress-track" aria-hidden="true"><span /></div>
        </div>
    );
}
