import { useEffect, useState } from 'react';

// Native radios and CSS own selection/content. React adds status and an optional shortcut.
export function WorkshopTracks({ tracks }) {
    const [active, setActive] = useState(null);
    const [enhanced, setEnhanced] = useState(false);
    useEffect(() => {
        const sync = () => setActive(tracks.findIndex(({ input }) => input.checked));
        tracks.forEach(({ input }) => input.addEventListener('change', sync));
        sync();
        setEnhanced(true);
        return () => tracks.forEach(({ input }) => input.removeEventListener('change', sync));
    }, [tracks]);
    const next = () => {
        const current = tracks.findIndex(({ input }) => input.checked);
        tracks[(current + 1) % tracks.length].input.click();
    };
    return (
        <div className="sjw-explorer-footer">
            <p className="sjw-current"><span className="sjw-label">{active === null ? 'ILLUSTRATIVE LEARNING TRACKS' : 'EXAMPLE / ' + String(active + 1).padStart(2, '0')}</span>
                <span aria-live="polite" aria-atomic="true">{active === null ? 'Observe. Experiment. Share.' : tracks[active]?.name.textContent}</span></p>
            <button className="sjw-next" type="button" hidden={!enhanced} onClick={next}>Next example <span aria-hidden="true">→</span></button>
        </div>
    );
}
