import React from 'react';
import { hydrateRoot } from 'react-dom/client';
import { IslandBoundary } from './components/IslandBoundary.jsx';
import { CompetitionRounds } from './components/CompetitionRounds.jsx';
import './styles/coding-competition.css';

const page = document.querySelector('.sj-coding');
const status = page?.querySelector('#snippetjam-coding-status');
const rounds = Array.from(page?.querySelectorAll('details[data-coding-round]') ?? []);

if (status && rounds.length && rounds.every((round) => round.querySelector('.sjc-round-name'))) {
    const fallback = status.innerHTML;
    try {
        hydrateRoot(status, <IslandBoundary fallback={fallback}><CompetitionRounds rounds={rounds} /></IslandBoundary>);
    } catch (error) {
        status.innerHTML = fallback;
        console.error('Snippet Jam round desk enhancement unavailable.', error);
    }
}

const menu = page?.querySelector('.sjc-menu');
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
