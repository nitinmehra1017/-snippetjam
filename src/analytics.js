export function trackSnippetJamEvent(name, params = {}) {
    if (typeof window !== 'undefined' && typeof window.gtag === 'function') {
        window.gtag('event', name, params);
    }
}
