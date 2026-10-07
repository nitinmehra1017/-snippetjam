import { Component } from 'react';

// Recover to the original, trusted WordPress HTML if an interactive island fails.
export class IslandBoundary extends Component {
    state = { failed: false };

    static getDerivedStateFromError() {
        return { failed: true };
    }

    componentDidCatch(error) {
        console.error('Snippet Jam interaction unavailable.', error);
    }

    render() {
        return this.state.failed
            ? <div dangerouslySetInnerHTML={{ __html: this.props.fallback }} />
            : this.props.children;
    }
}
