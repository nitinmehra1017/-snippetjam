import { trackSnippetJamEvent } from '../analytics.js';
import { useEffect, useState } from 'react';
import diagram from '../../assets/event-diagram.svg?raw';

export function EventIndex({ events }) {
    const [activeIndex, setActiveIndex] = useState(0);
    const [enhanced, setEnhanced] = useState(false);
    useEffect(() => setEnhanced(true), []);
    const active = events[activeIndex];

    return (
        <div className="sj-events-layout">
            <div className="sj-event-list">
                {events.map((event, index) => (
                    <div className="sj-event-row" data-active={index === activeIndex ? 'true' : 'false'} key={event.slug}
                        onMouseEnter={() => setActiveIndex(index)} onFocus={() => setActiveIndex(index)}>
                        <a className="sj-event-link" href={event.url}>
                            <span className="sj-event-number">{event.number}</span>
                            <span className="sj-event-title">{event.title}</span>
                        </a>
                        <button className="sj-event-switch" type="button" hidden={!enhanced}
                            aria-label={'Preview ' + event.title} aria-pressed={index === activeIndex}
                            onClick={() => { setActiveIndex(index); trackSnippetJamEvent('event_preview', { event_name: event.slug }); }}><span aria-hidden="true">↗</span></button>
                    </div>
                ))}
            </div>
            <div className="sj-event-preview" aria-live="polite" aria-atomic="true">
                <div className="sj-event-visual" data-event={active.slug} aria-hidden="true"
                    dangerouslySetInnerHTML={{ __html: diagram }} />
                <div className="sj-event-caption">
                    <p className="sj-eyebrow">{active.number + ' / ' + active.mode}</p>
                    <h3>{active.title}</h3>
                    <p>{active.description}</p>
                    <a className="sj-text-link" href={active.url}>Explore event <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </div>
    );
}
