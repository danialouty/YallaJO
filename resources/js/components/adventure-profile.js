document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('[data-adventure-booking]');
    if (!container) return;

    const price = Number(container.dataset.price || 0);
    const capacity = Number(container.dataset.capacity || 0);
    const peopleCount = document.getElementById('peopleCount');
    const peopleInput = document.getElementById('peopleInput');
    const totalPrice = document.getElementById('totalPrice');
    const increase = document.getElementById('increase');
    const decrease = document.getElementById('decrease');
    let people = 2;

    function updateBooking() {
        if (!peopleCount || !peopleInput || !totalPrice) return;
        peopleCount.textContent = people;
        peopleInput.value = people;
        totalPrice.textContent = (price * people).toFixed(2);
    }

    increase?.addEventListener('click', () => {
        if (!capacity || people < capacity) {
            people++;
            updateBooking();
        }
    });

    decrease?.addEventListener('click', () => {
        if (people > 1) {
            people--;
            updateBooking();
        }
    });

    updateBooking();
});
