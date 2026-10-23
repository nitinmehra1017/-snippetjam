import React from 'react';
import { hydrateRoot } from 'react-dom/client';
import { IslandBoundary } from './components/IslandBoundary.jsx';
import { WorkshopTracks } from './components/WorkshopTracks.jsx';
import './styles/workshops.css';

const page = document.querySelector('.sj-workshops');
const navigation = page?.querySelector('#snippetjam-workshops-navigation');
const tracks = Array.from(page?.querySelectorAll('.sjw-track-option') ?? []).map((option) => ({
    input: option.querySelector('input[type="radio"]'),
    name: option.querySelector('.sjw-track-name')
}));
if (navigation && tracks.length && tracks.every(({ input, name }) => input && name)) {
    const fallback = navigation.innerHTML;
    try {
        hydrateRoot(navigation, <IslandBoundary fallback={fallback}><WorkshopTracks tracks={tracks} /></IslandBoundary>);
    } catch (error) {
        navigation.innerHTML = fallback;
        console.error('Snippet Jam track navigation unavailable.', error);
    }
}
const menu = page?.querySelector('.sjw-menu');
if (menu) {
    menu.addEventListener('click', (event) => { if (event.target.closest('a')) menu.open = false; });
    menu.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menu.open) {
            menu.open = false;
            menu.querySelector('summary').focus();
        }
    });
}
