import { useEffect, useState } from 'react';

// The native disclosures and CSS own the exploration; React adds a spoken focus label.
export function RoboticsExplorer({ modules }) {
    const [active, setActive] = useState(null);
    useEffect(() => {
        const sync = () => setActive(modules.find((module) => module.open) ?? null);
        modules.forEach((module) => module.addEventListener('toggle', sync));
        sync();
        return () => modules.forEach((module) => module.removeEventListener('toggle', sync));
    }, [modules]);
    return (
        <p className="sjr-focus">
            <span className="sjr-label">{active ? 'EXPLORING / ' + active.querySelector('.sjr-module-number').textContent : 'SYSTEM VIEW'}</span>
            <span aria-live="polite" aria-atomic="true">{active ? active.querySelector('.sjr-module-name').textContent : 'Sense. Decide. Move.'}</span>
        </p>
    );
}
