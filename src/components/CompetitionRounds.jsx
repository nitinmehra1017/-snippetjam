import { useEffect, useState } from 'react';

// WordPress renders all prompt content; native details remain usable without React.
export function CompetitionRounds({ rounds }) {
    const [selection, setSelection] = useState(null);

    useEffect(() => {
        const sync = () => {
            const open = rounds.findIndex((round) => round.open);
            setSelection(open < 0 ? null : open);
        };
        rounds.forEach((round) => round.addEventListener('toggle', sync));
        sync();
        return () => rounds.forEach((round) => round.removeEventListener('toggle', sync));
    }, [rounds]);

    const label = selection === null
        ? 'Read. Solve. Verify.'
        : rounds[selection].querySelector('.sjc-round-name').textContent;

    return (
        <p className="sjc-round-status">
            <span className="sjc-status-number" aria-hidden="true">{selection === null ? '↗' : String(selection + 1).padStart(2, '0')}</span>
            <span aria-live="polite" aria-atomic="true">{label}</span>
        </p>
    );
}
