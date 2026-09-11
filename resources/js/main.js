document.addEventListener('DOMContentLoaded', function () {
    const heroSwiper = new Swiper('.hero-image', {
        slidesPerView: 1,
        loop: true,
        effect: 'fade',
        fadeEffect: {
            crossFade: true,
        },
        speed: 4000,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        navigation: {
            prevEl: '.hero-prev',
            nextEl: '.hero-next',
        },
        pagination: {
            el: '.hero-dots',
            clickable: true,
        },
        allowTouchMove: true,
        on: {
            init: function () {
                this.slides.forEach(function (slide) {
                    slide.classList.remove('zoom-active', 'zoom-hold');
                });

                const currentSlide = this.slides[this.activeIndex];

                if (currentSlide) {
                    currentSlide.classList.add('zoom-active');
                }
            },
            slideChangeTransitionStart: function () {
                const previousSlide = this.slides[this.previousIndex];
                const currentSlide = this.slides[this.activeIndex];

                this.slides.forEach(function (slide) {
                    slide.classList.remove('zoom-active');
                });

                if (previousSlide) {
                    previousSlide.classList.add('zoom-hold');
                }

                if (currentSlide) {
                    currentSlide.classList.remove('zoom-hold');
                    currentSlide.classList.add('zoom-active');
                }
            },
            slideChangeTransitionEnd: function () {
                this.slides.forEach(function (slide) {
                    slide.classList.remove('zoom-hold');
                });
            },
        },
    });
});

// 商品詳細
document.addEventListener('DOMContentLoaded', function () {
    const quantity = document.getElementById('quantity');
    const minus = document.getElementById('quantity-minus');
    const plus = document.getElementById('quantity-plus');

    if (!quantity || !minus || !plus) {
        return;
    }

    minus.addEventListener('click', function () {
        const currentValue = parseInt(quantity.value, 10) || 1;

        if (currentValue > 1) {
            quantity.value = currentValue - 1;
        }
    });

    plus.addEventListener('click', function () {
        const currentValue = parseInt(quantity.value, 10) || 1;

        if (currentValue < 10) {
            quantity.value = currentValue + 1;
        }
    });
});

// 検索
document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.querySelector('.header-search-button');
    const searchPanel = document.querySelector('.search-panel');

    if (!searchButton || !searchPanel) {
        return;
    }

    searchButton.addEventListener('click', function () {
        searchPanel.classList.toggle('is-open');

        if (searchPanel.classList.contains('is-open')) {
            const searchInput = searchPanel.querySelector('input');

            if (searchInput) {
                searchInput.focus();
            }
        }
    });
});
