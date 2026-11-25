document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.landing-hero')) {
        document.body.classList.add('landing-scroll-mode');

        const revealLandingControls = () => {
            if (window.scrollY <= 24) return;

            document.body.classList.add('has-scrolled');
            window.removeEventListener('scroll', revealLandingControls);
        };

        revealLandingControls();
        window.addEventListener('scroll', revealLandingControls, { passive: true });
    }

    const cards = document.querySelectorAll('.adventure-grid .reveal-card');
    const landingSections = document.querySelectorAll('.landing-reveal');
    const revealTargets = [...cards, ...landingSections];
    if (!revealTargets.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        revealTargets.forEach(target => target.classList.add('is-visible'));
        return;
    }

    if (!('IntersectionObserver' in window)) {
        revealTargets.forEach(target => target.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.04,
        rootMargin: '0px 0px 220px',
    });

    revealTargets.forEach(target => observer.observe(target));
});
