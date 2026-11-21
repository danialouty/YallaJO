document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-review-delete-trigger]').forEach(trigger => {
        const modalId = trigger.dataset.reviewDeleteTrigger;
        const fallback = document.getElementById(modalId + '-fallback');
        const dialog = document.getElementById(modalId);
        const form = document.getElementById(modalId + '-form');
        const content = document.getElementById(modalId + '-content');
        const cancel = document.querySelector(`[data-review-delete-cancel="${modalId}"]`);

        trigger.addEventListener('click', event => {
            if (!dialog || dialog.open) return;

            content?.append(form);

            try {
                dialog.showModal();
                event.preventDefault();
                fallback.open = false;
                cancel?.focus();
            } catch {
                fallback?.append(form);
            }
        });

        cancel?.addEventListener('click', event => {
            if (!dialog?.open) return;
            event.preventDefault();
            dialog.close();
        });

        dialog?.addEventListener('close', () => {
            fallback?.append(form);
            trigger.focus();
        });
    });
});
