import React from 'react';
import { hydrateRoot } from 'react-dom/client';
import { EventIndex } from './components/EventIndex.jsx';
import { JourneyProgress } from './components/JourneyProgress.jsx';
import { IslandBoundary } from './components/IslandBoundary.jsx';
import './styles/main.css';

function enhanceIsland(id, element) {
    const root = document.getElementById(id);
    if (!root) return;
    const fallback = root.innerHTML;
    try {
        hydrateRoot(root, <IslandBoundary fallback={fallback}>{element}</IslandBoundary>);
    } catch (error) {
        root.innerHTML = fallback;
        console.error('Snippet Jam enhancement unavailable.', error);
    }
}

const dataElement = document.getElementById('snippetjam-homepage-data');
if (dataElement) {
    try {
        const data = JSON.parse(dataElement.textContent);
        if (Array.isArray(data.events) && data.events.length === 4) {
            enhanceIsland('snippetjam-react-root', <EventIndex events={data.events} />);
        }
    } catch (error) {
        // Keep all server-rendered links/content if the data cannot be read.
        console.error('Snippet Jam event preview unavailable.', error);
    }
}
enhanceIsland('snippetjam-journey-progress', <JourneyProgress />);

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
if (!reducedMotion.matches && 'IntersectionObserver' in window) {
    const reveal = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.dataset.entered = 'true';
                reveal.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('[data-reveal]').forEach((section) => reveal.observe(section));
    reducedMotion.addEventListener('change', (event) => { if (event.matches) reveal.disconnect(); });
}

const menu = document.querySelector('.sj-mobile-menu');
if (menu) {
    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) menu.open = false;
    });
    menu.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menu.open) {
            menu.open = false;
            menu.querySelector('summary').focus();
        }
    });
}
