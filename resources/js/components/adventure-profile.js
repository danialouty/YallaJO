document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('[data-adventure-booking]');
    if (!container) return;

    const bookingSuccess = document.getElementById('adventure-booking-success');
    const closeBookingSuccess = () => bookingSuccess?.classList.add('is-hidden');
    bookingSuccess?.querySelector('[data-close-adventure-booking-success]')
        ?.addEventListener('click', closeBookingSuccess);
    bookingSuccess?.addEventListener('click', event => {
        if (event.target === bookingSuccess) closeBookingSuccess();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeBookingSuccess();
            closePayment?.();
        }
    });

    const paymentModal = document.getElementById('adventure-payment-modal');
    const paymentForm = document.getElementById('payment-details-form');
    const bookingForm = document.getElementById('bookingForm');
    const paymentError = paymentModal?.querySelector('[data-payment-error]');
    const cardNumber = document.getElementById('payment-card-number');
    const paymentTotal = paymentModal?.querySelector('[data-payment-total]');
    const closePayment = () => paymentModal?.classList.add('is-hidden');
    container.querySelector('[data-open-payment]')?.addEventListener('click', () => {
        if (paymentTotal) paymentTotal.textContent = (price * people).toFixed(2);
        paymentModal?.classList.remove('is-hidden');
        document.getElementById('payment-cardholder')?.focus();
    });
    paymentModal?.querySelector('[data-close-payment]')?.addEventListener('click', closePayment);
    paymentModal?.addEventListener('click', event => {
        if (event.target === paymentModal) closePayment();
    });
    paymentForm?.addEventListener('submit', event => {
        event.preventDefault();
        const digits = (cardNumber?.value || '').replace(/\D/g, '');
        const expiry = document.getElementById('payment-expiry')?.value.trim() || '';
        const cvv = document.getElementById('payment-cvv')?.value.trim() || '';
        if (!document.getElementById('payment-cardholder')?.value.trim() || digits.length < 12 || digits.length > 19 || !/^\d{2}\/\d{2}$/.test(expiry) || !/^\d{3,4}$/.test(cvv)) {
            if (paymentError) paymentError.textContent = 'يرجى إدخال بيانات بطاقة صحيحة لإكمال الحجز.';
            return;
        }
        const method = bookingForm?.querySelector('[name="payment_method"]');
        if (method) method.value = 'Visa';
        bookingForm?.requestSubmit();
    });
    cardNumber?.addEventListener('input', () => {
        const digits = cardNumber.value.replace(/\D/g, '').slice(0, 19);
        cardNumber.value = digits.replace(/(.{4})/g, '$1 ').trim();
    });
    document.getElementById('payment-expiry')?.addEventListener('input', event => {
        const digits = event.target.value.replace(/\D/g, '').slice(0, 4);
        event.target.value = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
    });

    const price = Number(container.dataset.price || 0);
    const capacity = Number(container.dataset.capacity || 0);
    const peopleCount = document.getElementById('peopleCount');
    const peopleInput = document.getElementById('peopleInput');
    const totalPrice = document.getElementById('totalPrice');
    const increase = document.getElementById('increase');
    const decrease = document.getElementById('decrease');
    let people = Number(peopleInput?.value || 1);

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

    const gallery = document.querySelector('[data-adventure-gallery]');
    const mainImage = document.getElementById('gallery-main-image');
    const thumbnails = gallery?.querySelectorAll('[data-gallery-src]');
    const expandButton = gallery?.querySelector('[data-gallery-expand]');

    thumbnails?.forEach(thumbnail => {
        thumbnail.addEventListener('click', () => {
            thumbnails.forEach(item => item.classList.remove('is-active'));
            thumbnail.classList.add('is-active');

            if (!mainImage) return;
            mainImage.classList.add('is-changing');
            window.setTimeout(() => {
                mainImage.src = thumbnail.dataset.gallerySrc;
                mainImage.classList.remove('is-changing');
            }, 180);
        });
    });

    expandButton?.addEventListener('click', () => {
        if (!mainImage?.src) return;
        const lightbox = document.createElement('dialog');
        lightbox.className = 'gallery-lightbox';
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'gallery-lightbox-close';
        closeButton.setAttribute('aria-label', 'إغلاق الصورة');
        closeButton.textContent = '×';
        const lightboxImage = document.createElement('img');
        lightboxImage.src = mainImage.src;
        lightboxImage.alt = mainImage.alt;
        lightbox.append(closeButton, lightboxImage);
        document.body.appendChild(lightbox);
        lightbox.showModal();
        closeButton.addEventListener('click', () => lightbox.close());
        lightbox.addEventListener('close', () => lightbox.remove());
        lightbox.addEventListener('click', event => {
            if (event.target === lightbox) lightbox.close();
        });
    });
});
