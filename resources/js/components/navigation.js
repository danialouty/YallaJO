document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-logout]').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            link.closest('form')?.submit();
        });
    });
});
