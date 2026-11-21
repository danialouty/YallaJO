document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.adventure-modal').forEach(modal => {
        modal.querySelectorAll('.modal-close, [data-close-modal]').forEach(button => {
            button.addEventListener('click', () => modal.close());
        });
        modal.addEventListener('click', event => {
            if (event.target === modal) modal.close();
        });
    });

    document.querySelectorAll('.adventure-modal[data-open-optional="true"]').forEach(modal => {
        modal.showModal();
    });
});
