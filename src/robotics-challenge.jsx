import React from 'react';
import { hydrateRoot } from 'react-dom/client';
import { IslandBoundary } from './components/IslandBoundary.jsx';
import { RoboticsExplorer } from './components/RoboticsExplorer.jsx';
import './styles/robotics-challenge.css';

const page = document.querySelector('.sj-robotics');
const focus = page?.querySelector('#snippetjam-robotics-focus');
const modules = Array.from(page?.querySelectorAll('[data-robotics-module]') ?? []);
if (focus && modules.length && modules.every((module) => module.querySelector('.sjr-module-name') && module.querySelector('.sjr-module-number'))) {
    const fallback = focus.innerHTML;
    try {
        hydrateRoot(focus, <IslandBoundary fallback={fallback}><RoboticsExplorer modules={modules} /></IslandBoundary>);
    } catch (error) {
        focus.innerHTML = fallback;
        console.error('Snippet Jam robotics enhancement unavailable.', error);
    }
}
const menu = page?.querySelector('.sjr-menu');
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
