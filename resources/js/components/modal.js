
function findModal(id) {
    if (!id) {
        return null;
    }

    const modal = document.getElementById(id);

    return modal instanceof HTMLDialogElement
        ? modal
        : null;
}

function openModal(modal) {
    if (modal && !modal.open) {
        modal.showModal();
    }
}

function closeModal(modal) {
    if (modal && modal.open) {
        modal.close();
    }
}

document.addEventListener('click', (event) => {
    const target = event.target;

    if (!(target instanceof Element)) {
        return;
    }

    // Open a modal
    const openButton = target.closest('[data-modal-open]');

    if (openButton) {
        const modalId = openButton.dataset.modalOpen;

        openModal(findModal(modalId));

        return;
    }

    // Close a modal
    const closeButton = target.closest('[data-modal-close]');

    if (closeButton) {
        const modal = closeButton.closest('dialog');

        closeModal(modal);

        return;
    }

    // Close when clicking outside the dialog content
    if (target instanceof HTMLDialogElement && target.open) {
        const rect = target.getBoundingClientRect();

        const clickedOutside =
            event.clientX < rect.left ||
            event.clientX > rect.right ||
            event.clientY < rect.top ||
            event.clientY > rect.bottom;

        if (clickedOutside) {
            closeModal(target);
        }
    }
});

// Reopen the correct modal after validation errors
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector(
        'dialog[data-modal-reopen]'
    );

    if (modal instanceof HTMLDialogElement) {
        openModal(modal);
    }
});
