import React from 'react';
import { hydrateRoot } from 'react-dom/client';
import { IslandBoundary } from './components/IslandBoundary.jsx';
import { HackathonJourney } from './components/HackathonJourney.jsx';
import './styles/hackathon.css';

const page = document.querySelector('.sj-hackathon');
const root = page?.querySelector('#snippetjam-hackathon-progress');
const data = page?.querySelector('#snippetjam-hackathon-data');
if (root && data) {
    const fallback = root.innerHTML;
    try {
        const { steps } = JSON.parse(data.textContent);
        if (Array.isArray(steps) && steps.length === 7 && steps.every((step) => typeof step === 'string')) {
            hydrateRoot(root, <IslandBoundary fallback={fallback}><HackathonJourney steps={steps} /></IslandBoundary>);
        }
    } catch (error) {
        root.innerHTML = fallback;
        console.error('Snippet Jam build-log enhancement unavailable.', error);
    }
}

const menu = page?.querySelector('.sjh-menu');
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
