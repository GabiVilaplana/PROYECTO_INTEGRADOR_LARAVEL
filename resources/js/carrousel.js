// resources/js/carrousel.js

document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');
    if (!track) return;

    const slides = Array.from(track.children);
    const btnLeft = document.getElementById('btn-left');
    const btnRight = document.getElementById('btn-right');
    const wrapper = track.closest('.carousel-wrapper') || track.parentElement;

    if (slides.length === 0) return;

    // Apply smooth transition
    track.style.transition = 'transform 520ms cubic-bezier(.22,.95,.38,1)';
    track.style.willChange = 'transform';

    let slideWidth = slides[0].getBoundingClientRect().width;
    let itemsPerView = Math.max(1, Math.floor((wrapper.getBoundingClientRect().width) / slideWidth));
    let currentIndex = 0;

    const updateMetrics = () => {
        slideWidth = slides[0].getBoundingClientRect().width;
        itemsPerView = Math.max(1, Math.floor((wrapper.getBoundingClientRect().width) / slideWidth));
        // Ensure currentIndex is valid after resize
        const maxIndex = Math.max(0, slides.length - itemsPerView);
        if (currentIndex > maxIndex) currentIndex = maxIndex;
        moveToIndex(currentIndex, false);
        updateButtons();
    };

    const moveToIndex = (index, animate = true) => {
        const maxIndex = Math.max(0, slides.length - itemsPerView);
        const clamped = Math.max(0, Math.min(index, maxIndex));
        currentIndex = clamped;

        if (!animate) {
            // temporarily disable transition
            const prev = track.style.transition;
            track.style.transition = 'none';
            track.style.transform = `translateX(${-clamped * slideWidth}px)`;
            // force reflow then restore
            // eslint-disable-next-line no-unused-expressions
            track.getBoundingClientRect();
            track.style.transition = prev;
        } else {
            track.style.transform = `translateX(${-clamped * slideWidth}px)`;
        }
        updateButtons();
    };

    const updateButtons = () => {
        if (!btnLeft || !btnRight) return;
        const maxIndex = Math.max(0, slides.length - itemsPerView);
        btnLeft.disabled = currentIndex <= 0;
        btnRight.disabled = currentIndex >= maxIndex;
        btnLeft.classList.toggle('disabled', btnLeft.disabled);
        btnRight.classList.toggle('disabled', btnRight.disabled);
    };

    // Attach events safely
    if (btnRight) btnRight.addEventListener('click', () => moveToIndex(currentIndex + 1));
    if (btnLeft) btnLeft.addEventListener('click', () => moveToIndex(currentIndex - 1));

    // Recompute on resize
    window.addEventListener('resize', () => {
        // debounce: simple timeout
        clearTimeout(window._carouselResizeTimeout);
        window._carouselResizeTimeout = setTimeout(updateMetrics, 120);
    });

    // initialise
    updateMetrics();
});