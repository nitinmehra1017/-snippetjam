import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles/main.css';

function SnippetJamApp() {
    return (
        <section className="sj-react-test">
            <p>SNIPPET JAM / 2026</p>

            <h1>
                CODE.<br />
                COMPETE.<br />
                CREATE.
            </h1>

            <p>React frontend successfully loaded.</p>
        </section>
    );
}

const rootElement = document.getElementById('snippetjam-react-root');

if (rootElement) {
    createRoot(rootElement).render(<SnippetJamApp />);
}
