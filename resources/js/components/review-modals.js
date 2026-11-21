document.addEventListener('click', event => {
    const trigger = event.target.closest('[data-review-delete-trigger]');
    if (!trigger) return;

    const modalId = trigger.dataset.reviewDeleteTrigger;
    const dialog = document.getElementById(modalId);
    const fallback = document.getElementById(modalId + '-fallback');
    const form = document.getElementById(modalId + '-form');
    const content = document.getElementById(modalId + '-content');
    if (!dialog || dialog.open) return;

    content?.append(form);
    try {
        dialog.showModal();
        fallback.open = false;
        event.preventDefault();
    } catch {
        fallback?.append(form);
    }
});
