document.addEventListener('DOMContentLoaded', () => {
    const successModal = document.getElementById('booking-success-modal');
    document.querySelectorAll('[data-close-success-modal]').forEach(button => {
        button.addEventListener('click', () => {
            successModal?.classList.add('is-hidden');
        });
    });
    successModal?.addEventListener('click', event => {
        if (event.target === successModal) {
            successModal.classList.add('is-hidden');
        }
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            successModal?.classList.add('is-hidden');
        }
    });
    const createModal = document.getElementById('createBookingModal');
    const editModal = document.getElementById('editBookingModal');
    const createCount = document.getElementById('create_participants_count');
    const createTotal = document.getElementById('create_total_amount');
    const adventureSelect = document.getElementById('create_adventure_id');
    const editCount = document.getElementById('edit_participants_count');
    const editTotal = document.getElementById('edit_total_amount');
    let editUnitPrice = 0;

    function updateCreateTotal() {
        if (!adventureSelect || !createCount || !createTotal) return;
        const option = adventureSelect.options[adventureSelect.selectedIndex];
        const price = Number(option?.dataset.price || 0);
        createTotal.textContent = (price * Number(createCount.value || 1)).toFixed(2);
    }

    document.querySelectorAll('[data-open-create-modal]').forEach(button => {
        button.addEventListener('click', () => {
            createModal?.classList.remove('hidden');
            updateCreateTotal();
        });
    });

    document.querySelectorAll('[data-close-create-modal]').forEach(button => {
        button.addEventListener('click', () => createModal?.classList.add('hidden'));
    });

    adventureSelect?.addEventListener('change', updateCreateTotal);
    createCount?.addEventListener('input', updateCreateTotal);

    document.querySelectorAll('[data-open-edit-modal]').forEach(button => {
        button.addEventListener('click', () => {
            editUnitPrice = Number(button.dataset.unitPrice || 0);
            const form = document.getElementById('editBookingForm');
            form.action = `/bookings/${button.dataset.id}`;
            document.getElementById('edit_booking_id_label').textContent = `#${button.dataset.id}`;
            document.getElementById('edit_adventure_title').textContent = button.dataset.title;
            editCount.value = button.dataset.count;
            editTotal.textContent = (editUnitPrice * Number(editCount.value || 1)).toFixed(2);
            editModal?.classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-close-edit-modal]').forEach(button => {
        button.addEventListener('click', () => editModal?.classList.add('hidden'));
    });

    editCount?.addEventListener('input', () => {
        editTotal.textContent = (editUnitPrice * Number(editCount.value || 1)).toFixed(2);
    });

    [createModal, editModal].forEach(modal => {
        modal?.addEventListener('click', event => {
            if (event.target === modal) modal.classList.add('hidden');
        });
    });
});
