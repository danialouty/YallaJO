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
