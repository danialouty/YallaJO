document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.adventure-grid .reveal-card');
    if (!cards.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        cards.forEach(card => card.classList.add('is-visible'));
        return;
    }

    if (!('IntersectionObserver' in window)) {
        cards.forEach(card => card.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px',
    });

    cards.forEach(card => observer.observe(card));
});
