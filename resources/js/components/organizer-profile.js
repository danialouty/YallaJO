document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('organizer-profile-modal');
    if (!modal) return;

    const typeSelect = document.getElementById('organizer_type');
    const licenseHint = document.getElementById('license-hint');

    function syncHints() {
        if (!typeSelect || !licenseHint) return;
        const isOffice = typeSelect.value === 'tourism_office';
        licenseHint.textContent = isOffice
            ? licenseHint.dataset.officeHint
            : licenseHint.dataset.groupHint;
        licenseHint.className = isOffice
            ? 'text-xs text-amber-400'
            : 'text-xs text-slate-400';
    }

    typeSelect?.addEventListener('change', syncHints);
    syncHints();

    document.querySelectorAll('[data-open-organizer-profile]').forEach(button => {
        button.addEventListener('click', () => modal.showModal());
    });

    modal.querySelectorAll('[data-close-organizer-profile]').forEach(button => {
        button.addEventListener('click', () => modal.close());
    });

    if (modal.dataset.profileErrors === 'true') {
        modal.showModal();
    }
});
