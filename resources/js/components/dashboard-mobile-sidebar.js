
function initializeDashboardMobileSidebar() {
    const menu = document.getElementById(
        'dashboard-mobile-menu'
    );

    const trigger = document.querySelector(
        '[data-dashboard-menu-open]'
    );

    // The current page may not use Dashboard Layout.
    if (
        !(menu instanceof HTMLDialogElement) ||
        !(trigger instanceof HTMLButtonElement)
    ) {
        return;
    }

    // Open the mobile sidebar.
    trigger.addEventListener('click', () => {
        if (menu.open) {
            return;
        }

        menu.showModal();

        trigger.setAttribute('aria-expanded', 'true');
    });

    // Handle close button, links, and backdrop.
    menu.addEventListener('click', (event) => {
        const target = event.target;

        if (!(target instanceof Element)) {
            return;
        }

        // Close button.
        if (target.closest('[data-dashboard-menu-close]')) {
            menu.close();
            return;
        }

        // Close when a navigation link is selected.
        if (target.closest('nav a')) {
            menu.close();
            return;
        }

        // Close when clicking outside the panel.
        if (target === menu) {
            const rect = menu.getBoundingClientRect();

            const outside =
                event.clientX < rect.left ||
                event.clientX > rect.right ||
                event.clientY < rect.top ||
                event.clientY > rect.bottom;

            if (outside) {
                menu.close();
            }
        }
    });

    // Keep ARIA state synchronized,
    // including closing with the Escape key.
    menu.addEventListener('close', () => {
        trigger.setAttribute('aria-expanded', 'false');
    });

    // Close the mobile panel if the screen
    // becomes desktop-sized.
    const desktopScreen = window.matchMedia(
        '(min-width: 64rem)'
    );

    desktopScreen.addEventListener('change', (event) => {
        if (event.matches && menu.open) {
            menu.close();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener(
        'DOMContentLoaded',
        initializeDashboardMobileSidebar
    );
} else {
    initializeDashboardMobileSidebar();
}
