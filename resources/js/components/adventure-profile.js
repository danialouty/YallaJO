document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('[data-adventure-booking]');
    if (!container) return;

    const price = Number(container.dataset.price);
    const capacity = Number(container.dataset.capacity);
    let people = 2;
    const peopleCount = document.getElementById('peopleCount');
    const peopleInput = document.getElementById('peopleInput');
    const totalPrice = document.getElementById('totalPrice');

    function updateBooking() {
        peopleCount.textContent = people;
        peopleInput.value = people;
        totalPrice.textContent = (price * people).toFixed(2);
    }

    document.getElementById('increase')?.addEventListener('click', () => {
        if (people < capacity) {
            people++;
            updateBooking();
        }
    });

    document.getElementById('decrease')?.addEventListener('click', () => {
        if (people > 1) {
            people--;
            updateBooking();
        }
    });

    document.getElementById('confirmBooking')?.addEventListener('click', () => {
        alert('واجهة الحجز جاهزة، اربطها بمسار الحجز عند تفعيله.');
    });

    updateBooking();
});
