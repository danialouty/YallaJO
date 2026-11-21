document.addEventListener('DOMContentLoaded', () => {
    const createModal = document.getElementById('createBookingModal');
    const openCreateButtons = document.querySelectorAll('[data-open-create-modal]');
    const closeCreateButtons = document.querySelectorAll('[data-close-create-modal]');
    const adventureSelect = document.getElementById('create_adventure_id');
    const createCountInput = document.getElementById('create_participants_count');
    const createTotal = document.getElementById('create_total_amount');

    function updateCreateTotal() {
        if (!adventureSelect || !createCountInput || !createTotal) return;
        const selectedOption = adventureSelect.options[adventureSelect.selectedIndex];
        const price = parseFloat(selectedOption?.dataset.price || 0);
        const count = parseInt(createCountInput.value || 1);
        createTotal.textContent = (price * count).toFixed(2);
    }

    openCreateButtons.forEach(button => button.addEventListener('click', () => {
        createModal?.classList.remove('hidden');
        updateCreateTotal();
    }));

    closeCreateButtons.forEach(button => button.addEventListener('click', () => {
        createModal?.classList.add('hidden');
    }));

    adventureSelect?.addEventListener('change', updateCreateTotal);
    createCountInput?.addEventListener('input', updateCreateTotal);

    document.getElementById('create_minus_btn')?.addEventListener('click', () => {
        if (parseInt(createCountInput.value) > 1) {
            createCountInput.value = parseInt(createCountInput.value) - 1;
            updateCreateTotal();
        }
    });

    document.getElementById('create_plus_btn')?.addEventListener('click', () => {
        createCountInput.value = parseInt(createCountInput.value) + 1;
        updateCreateTotal();
    });

    const editModal = document.getElementById('editBookingModal');
    const editForm = document.getElementById('editBookingForm');
    const editTitle = document.getElementById('edit_adventure_title');
    const editBookingId = document.getElementById('edit_booking_id_label');
    const editCountInput = document.getElementById('edit_participants_count');
    const editTotal = document.getElementById('edit_total_amount');
    let currentEditUnitPrice = 0;

    function updateEditTotal() {
        if (!editCountInput || !editTotal) return;
        const count = parseInt(editCountInput.value || 1);
        editTotal.textContent = (currentEditUnitPrice * count).toFixed(2);
    }

    document.querySelectorAll('[data-open-edit-modal]').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.dataset.id;
            currentEditUnitPrice = parseFloat(button.dataset.unitPrice || 0);
            editForm.action = `/bookings/${id}`;
            editBookingId.textContent = `#${id}`;
            editTitle.textContent = button.dataset.title;
            editCountInput.value = button.dataset.count;
            updateEditTotal();
            editModal?.classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-close-edit-modal]').forEach(button => {
        button.addEventListener('click', () => editModal?.classList.add('hidden'));
    });

    editCountInput?.addEventListener('input', updateEditTotal);

    document.getElementById('edit_minus_btn')?.addEventListener('click', () => {
        if (parseInt(editCountInput.value) > 1) {
            editCountInput.value = parseInt(editCountInput.value) - 1;
            updateEditTotal();
        }
    });

    document.getElementById('edit_plus_btn')?.addEventListener('click', () => {
        editCountInput.value = parseInt(editCountInput.value) + 1;
        updateEditTotal();
    });

    [createModal, editModal].forEach(modal => {
        modal?.addEventListener('click', event => {
            if (event.target === modal) modal.classList.add('hidden');
        });
    });
});
