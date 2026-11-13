import Swiper from 'swiper';
import { Pagination, Navigation, Autoplay, EffectFade, Keyboard, Thumbs } from 'swiper/modules';
import { CountUp } from 'countup.js';

(function ($) {
    "use strict";

    /**
     * Search Modal interaction module
     */
    const modalSearch = () => {
        const btnSearch = document.getElementById('btn_search');
        const modal = document.getElementById('search-modal');

        if (!btnSearch || !modal) return;

        const btnClose = modal.querySelector('.search-modal__close');
        const overlay = modal.querySelector('.search-modal__overlay');
        const input = modal.querySelector('.search-modal__input');
        const form = modal.querySelector('.search-modal__form');

        let isOpen = false;
        let lastFocusedElement = null;

        // Ensure ARIA setup on trigger button
        btnSearch.setAttribute('aria-controls', 'search-modal');
        btnSearch.setAttribute('aria-haspopup', 'dialog');
        btnSearch.setAttribute('aria-expanded', 'false');

        const openModal = () => {
            if (isOpen) return;
            isOpen = true;
            lastFocusedElement = document.activeElement;

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            btnSearch.setAttribute('aria-expanded', 'true');
            document.body.classList.add('search-modal-open');

            // Shift focus to search input after modal is visible
            setTimeout(() => {
                if (input) {
                    input.focus();
                    if (input.value) {
                        input.select();
                    }
                }
            }, 60);
        };

        const closeModal = () => {
            if (!isOpen) return;
            isOpen = false;

            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            btnSearch.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('search-modal-open');

            // Return focus to search button or previous active element
            if (btnSearch && typeof btnSearch.focus === 'function') {
                btnSearch.focus();
            } else if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
                lastFocusedElement.focus();
            }
        };

        // Open modal on search button click
        btnSearch.addEventListener('click', (e) => {
            e.preventDefault();
            if (isOpen) {
                closeModal();
            } else {
                openModal();
            }
        });

        // Close modal on close button click
        if (btnClose) {
            btnClose.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        // Close modal on overlay backdrop click
        if (overlay) {
            overlay.addEventListener('click', (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        // Close modal on clicking outer backdrop area
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Keyboard navigation: Escape key & Focus Trap
        document.addEventListener('keydown', (e) => {
            if (!isOpen) return;

            if (e.key === 'Escape' || e.key === 'Esc') {
                e.preventDefault();
                closeModal();
                return;
            }

            // Keyboard Focus Trap within modal dialog
            if (e.key === 'Tab') {
                const focusable = modal.querySelectorAll(
                    'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                );

                if (!focusable.length) return;

                const firstEl = focusable[0];
                const lastEl = focusable[focusable.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === firstEl) {
                        lastEl.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastEl) {
                        firstEl.focus();
                        e.preventDefault();
                    }
                }
            }
        });

        // Search Form submit validation
        if (form) {
            form.addEventListener('submit', (e) => {
                if (input && !input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                }
            });
        }
    };

    /**
     * Hero Section Swiper Slider
     */
    const heroSlider = () => {
        const sliderElements = document.querySelectorAll('.hero-section__sliders');
        if (!sliderElements.length) return;

        // Shared 5-second autoplay delay matching Ken Burns animation (5s)
        const AUTOPLAY_DELAY = 5000;

        sliderElements.forEach((sliderEl) => {
            // Guard: ensure each slider instance is initialized only once
            if (sliderEl.swiper || sliderEl.classList.contains('swiper-initialized')) {
                return;
            }

            const slides = sliderEl.querySelectorAll('.swiper-slide');
            const slideCount = slides.length;
            if (slideCount === 0) return;

            // Scope pagination and navigation to current slider instance or immediate parent section
            const container = sliderEl.closest('.hero-section') || sliderEl;
            const paginationEl = sliderEl.querySelector('.swiper-pagination') || container.querySelector('.swiper-pagination');
            const nextEl = sliderEl.querySelector('.hero-slider-nav-btn--next') || sliderEl.querySelector('.swiper-button-next') || container.querySelector('.swiper-button-next');
            const prevEl = sliderEl.querySelector('.hero-slider-nav-btn--prev') || sliderEl.querySelector('.swiper-button-prev') || container.querySelector('.swiper-button-prev');

            // Custom hero controls bar
            const controlsEl = sliderEl.querySelector('.hero-slider-controls') || container.querySelector('.hero-slider-controls');
            const currentEl = controlsEl ? controlsEl.querySelector('.hero-slider-controls__current') : null;
            const totalEl = controlsEl ? controlsEl.querySelector('.hero-slider-controls__total') : null;
            const titleEl = controlsEl ? controlsEl.querySelector('.hero-slider-controls__title') : null;
            const progressEl = controlsEl ? controlsEl.querySelector('.hero-slider-controls__progress') : null;
            let segmentEls = controlsEl ? controlsEl.querySelectorAll('.hero-slider-progress-segment') : [];

            const hasPagination = Boolean(paginationEl);
            const hasNavigation = Boolean(nextEl && prevEl);
            const isMultipleSlides = slideCount > 1;

            // If only one slide, safely hide controls if they exist in markup
            if (!isMultipleSlides) {
                if (controlsEl) controlsEl.style.display = 'none';
                if (paginationEl) paginationEl.style.display = 'none';
                if (nextEl) nextEl.style.display = 'none';
                if (prevEl) prevEl.style.display = 'none';
            }

            // Helper to get or create fill element
            const getFillElement = (seg) => {
                let fill = seg.querySelector('.hero-slider-progress-segment__fill');
                if (!fill) {
                    fill = document.createElement('span');
                    fill.className = 'hero-slider-progress-segment__fill';
                    seg.appendChild(fill);
                }
                return fill;
            };

            // Fallback: generate segments dynamically if progress container exists but no segments rendered
            if (progressEl && segmentEls.length === 0 && isMultipleSlides) {
                for (let i = 0; i < slideCount; i++) {
                    const seg = document.createElement('button');
                    seg.type = 'button';
                    seg.className = 'hero-slider-progress-segment' + (i === 0 ? ' is-active' : '');
                    seg.setAttribute('data-index', String(i));
                    seg.setAttribute('aria-label', `Go to slide ${i + 1}`);
                    const fill = document.createElement('span');
                    fill.className = 'hero-slider-progress-segment__fill';
                    seg.appendChild(fill);
                    progressEl.appendChild(seg);
                }
                segmentEls = progressEl.querySelectorAll('.hero-slider-progress-segment');
            }

            // Real-time Autoplay Progress handler
            const handleAutoplayProgress = (swiperInstance, timeLeft, percentage) => {
                if (!segmentEls.length || !isMultipleSlides) return;

                const realIdx = typeof swiperInstance.realIndex === 'number'
                    ? swiperInstance.realIndex
                    : swiperInstance.activeIndex;

                // percentage: timeLeft / delay (starts at 1 and approaches 0)
                let p = percentage;
                if (typeof p !== 'number') {
                    const delay = (swiperInstance.params && swiperInstance.params.autoplay && swiperInstance.params.autoplay.delay) || AUTOPLAY_DELAY;
                    p = typeof timeLeft === 'number' ? timeLeft / delay : 0;
                }

                const progress = Math.max(0, Math.min(1, 1 - p));
                const percentStr = (progress * 100).toFixed(2) + '%';

                const activeSeg = segmentEls[realIdx];
                if (activeSeg) {
                    const fill = getFillElement(activeSeg);
                    fill.style.width = percentStr;
                }
            };

            // Sync controls function
            const syncHeroControls = (swiperInstance) => {
                if (!controlsEl || !isMultipleSlides) return;

                const realIdx = typeof swiperInstance.realIndex === 'number'
                    ? swiperInstance.realIndex
                    : swiperInstance.activeIndex;

                // 1. Counter "01 / 04"
                if (currentEl) {
                    currentEl.textContent = String(realIdx + 1).padStart(2, '0');
                }
                if (totalEl) {
                    totalEl.textContent = String(slideCount).padStart(2, '0');
                }

                // 2. Slide Title
                const originalSlideEl = slides[realIdx] || null;
                const activeSlideEl = swiperInstance.slides && swiperInstance.slides[swiperInstance.activeIndex]
                    ? swiperInstance.slides[swiperInstance.activeIndex]
                    : null;

                if (titleEl) {
                    const slideTitle = (originalSlideEl && originalSlideEl.getAttribute('data-title'))
                        || (activeSlideEl && activeSlideEl.getAttribute('data-title'))
                        || '';

                    titleEl.textContent = slideTitle;
                }

                // 3. Restart Ken Burns animation on active slide image to synchronize with slide start
                if (activeSlideEl) {
                    const activeImg = activeSlideEl.querySelector('.hero-slider-item__bg img');
                    if (activeImg) {
                        activeImg.style.animation = 'none';
                        void activeImg.offsetWidth; // Trigger reflow to restart animation from 0s
                        activeImg.style.animation = '';
                    }
                }

                // 4. Segmented Progress
                const isAutoplayEnabled = Boolean(
                    swiperInstance.params &&
                    swiperInstance.params.autoplay &&
                    swiperInstance.params.autoplay !== false &&
                    swiperInstance.params.autoplay.enabled !== false
                );

                if (segmentEls.length) {
                    segmentEls.forEach((seg, idx) => {
                        const fill = getFillElement(seg);
                        seg.classList.remove('is-completed', 'is-active', 'is-upcoming');

                        if (idx < realIdx) {
                            seg.classList.add('is-completed');
                            fill.style.width = '100%';
                        } else if (idx === realIdx) {
                            seg.classList.add('is-active');
                            fill.style.width = isAutoplayEnabled ? '0%' : '100%';
                        } else {
                            seg.classList.add('is-upcoming');
                            fill.style.width = '0%';
                        }
                    });
                }
            };

            // Build Swiper options
            const swiperOptions = {
                modules: [Pagination, Navigation, Autoplay, EffectFade, Keyboard],
                slidesPerView: 1,
                spaceBetween: 0,
                speed: 1000,
                watchSlidesProgress: true,
                grabCursor: isMultipleSlides,
                allowTouchMove: isMultipleSlides,
                loop: isMultipleSlides,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                autoplay: isMultipleSlides ? {
                    delay: AUTOPLAY_DELAY,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                } : false,
                keyboard: isMultipleSlides ? {
                    enabled: true,
                    onlyInViewport: true,
                } : false,
                pagination: hasPagination && isMultipleSlides ? {
                    el: paginationEl,
                    clickable: true,
                } : false,
                navigation: hasNavigation && isMultipleSlides ? {
                    nextEl: nextEl,
                    prevEl: prevEl,
                } : false,
                breakpoints: {
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    1200: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                },
                on: {
                    init: function (swiperInstance) {
                        syncHeroControls(swiperInstance);
                    },
                    slideChange: function (swiperInstance) {
                        syncHeroControls(swiperInstance);
                    },
                    autoplayTimeLeft: function (swiperInstance, timeLeft, percentage) {
                        handleAutoplayProgress(swiperInstance, timeLeft, percentage);
                    },
                },
            };

            try {
                const swiper = new Swiper(sliderEl, swiperOptions);

                // Initial sync check
                syncHeroControls(swiper);

                // Progress segment click navigation
                if (segmentEls.length && isMultipleSlides) {
                    segmentEls.forEach((seg) => {
                        seg.addEventListener('click', (e) => {
                            e.preventDefault();
                            const targetIdx = parseInt(seg.getAttribute('data-index'), 10);
                            if (!isNaN(targetIdx) && swiper) {
                                if (swiper.params.loop) {
                                    swiper.slideToLoop(targetIdx);
                                } else {
                                    swiper.slideTo(targetIdx);
                                }
                            }
                        });
                    });
                }
            } catch (err) {
                console.error('[Hoi An Travel] Error initializing hero slider:', err);
            }
        });
    };

    /**
     * Destination Section Interactive List & Image Preview
     */
    const destinationPreview = () => {
        const section = document.querySelector('.destination-section');
        if (!section) return;

        const list = section.querySelector('.destination-section__list') || section.querySelector('#destinations-editorial-list');
        const previewCard = section.querySelector('.destination-preview-card') || section.querySelector('#destination-preview-card');

        if (!list || !previewCard) return;

        // Prevent duplicate listener registration
        if (list.dataset.previewInitialized === 'true') return;
        list.dataset.previewInitialized = 'true';

        const rows = Array.from(list.querySelectorAll('.destination-row'));
        if (!rows.length) return;

        const previewImg = previewCard.querySelector('.destination-preview-card__image') || previewCard.querySelector('#preview-image');
        const previewTitle = previewCard.querySelector('.destination-preview-card__title') || previewCard.querySelector('#preview-title');
        const previewBadge = previewCard.querySelector('.destination-preview-card__badge-count') || previewCard.querySelector('#preview-badge');
        const previewDesc = previewCard.querySelector('.destination-preview-card__desc') || previewCard.querySelector('#preview-desc');
        const previewCta = previewCard.querySelector('.destination-preview-card__cta') || previewCard.querySelector('#preview-cta');

        // Preload valid images in background for instant, zero-flicker transitions
        rows.forEach((row) => {
            const imgUrl = row.getAttribute('data-image');
            if (imgUrl && imgUrl.trim() !== '') {
                const preload = new Image();
                preload.src = imgUrl;
            }
        });

        // Helper to update active location and preview card
        const setActiveDestination = (targetRow) => {
            if (!targetRow) return;

            // Update row active state
            rows.forEach((row) => {
                const isSelected = (row === targetRow);
                row.classList.toggle('active', isSelected);
                row.classList.toggle('is-active', isSelected);
                row.setAttribute('aria-selected', isSelected ? 'true' : 'false');
            });

            const imageUrl = targetRow.getAttribute('data-image');
            const title = targetRow.getAttribute('data-title');
            const badge = targetRow.getAttribute('data-badge');
            const desc = targetRow.getAttribute('data-desc');
            const ctaText = targetRow.getAttribute('data-cta');
            const link = targetRow.getAttribute('data-link');

            // Only update preview image if location has a valid image URL
            if (previewImg && imageUrl && imageUrl.trim() !== '') {
                const currentSrc = previewImg.getAttribute('src');
                if (currentSrc !== imageUrl) {
                    previewImg.classList.add('is-transitioning');
                    const temp = new Image();
                    temp.onload = () => {
                        previewImg.src = imageUrl;
                        if (title) previewImg.alt = title;
                        requestAnimationFrame(() => {
                            previewImg.classList.remove('is-transitioning');
                        });
                    };
                    temp.onerror = () => {
                        previewImg.classList.remove('is-transitioning');
                    };
                    temp.src = imageUrl;
                }
            }

            // Update title
            if (previewTitle && title) {
                previewTitle.textContent = title;
            }

            // Update badge (e.g. "15 TOURS")
            if (previewBadge && badge) {
                previewBadge.textContent = badge;
            }

            // Update description
            if (previewDesc) {
                if (desc && desc.trim() !== '') {
                    previewDesc.textContent = desc;
                    previewDesc.style.display = '';
                } else {
                    previewDesc.textContent = '';
                    previewDesc.style.display = 'none';
                }
            }

            // Update CTA link & text
            if (previewCta) {
                if (ctaText && ctaText.trim() !== '') {
                    previewCta.textContent = ctaText;
                }
                if (link && link.trim() !== '') {
                    previewCta.href = link;
                }
            }
        };

        // Attach event listeners
        rows.forEach((row) => {
            // Desktop hover
            row.addEventListener('mouseenter', () => {
                setActiveDestination(row);
            });

            // Click / Tap (desktop & touch devices)
            row.addEventListener('click', (e) => {
                // If user clicks a direct anchor inside row (if any), allow natural link navigation
                if (e.target.closest('a')) return;
                setActiveDestination(row);
            });

            // Keyboard accessibility (Enter / Space)
            row.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    setActiveDestination(row);
                }
            });
        });

        // Initialize from first row (or row marked active)
        const initialRow = rows.find((r) => r.classList.contains('active') || r.classList.contains('is-active')) || rows[0];
        if (initialRow) {
            setActiveDestination(initialRow);
        }
    };

    /**
     * Tours Section Swiper Carousel
     */
    const tourSwiper = () => {
        const swiperElements = document.querySelectorAll('.tour-swiper');
        if (!swiperElements.length) return;

        const updateCounter = (swiper) => {
            const current = String(swiper.realIndex + 1).padStart(2, '0');
            const total = String(swiper.slides.length).padStart(2, '0');
            const container = swiper.el.closest('.tours-section') || document;
            const counterEl = container.querySelector('.tour-swiper-counter') || document.querySelector('.tour-swiper-counter');
            if (counterEl) {
                counterEl.textContent = `${current} / ${total}`;
            }
        };

        swiperElements.forEach((el) => {
            if (el.swiper || el.classList.contains('swiper-initialized')) return;

            const container = el.closest('.tours-section') || el;
            const nextEl = container.querySelector('.tour-swiper-next') || '.tour-swiper-next';
            const prevEl = container.querySelector('.tour-swiper-prev') || '.tour-swiper-prev';

            try {
                new Swiper(el, {
                    modules: [Navigation],
                    slidesPerView: 1.15,
                    spaceBetween: 16,
                    speed: 600,
                    grabCursor: true,
                    watchOverflow: true,
                    loop: true,
                    navigation: {
                        nextEl: nextEl,
                        prevEl: prevEl,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2.1,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3.25, // Preserves the exact 3-full-card + 1-peek-in desktop layout
                            spaceBetween: 24,
                        },
                    },
                    on: {
                        init: function () {
                            updateCounter(this);
                        },
                        slideChange: function () {
                            updateCounter(this);
                        },
                    },
                });
            } catch (err) {
                console.error('[Hoi An Travel] Error initializing tour swiper:', err);
            }
        });
    };

    /**
     * FAQ Accordion Interaction Module
     * Handles accessible toggling, keyboard navigation, and smooth accordion animation
     * for all .faqs-list and .faq-item elements across the site.
     */
    const initFaqAccordion = () => {
        $(document).on('click', '.faq-item__question', function (e) {
            e.preventDefault();
            const $trigger = $(this);
            const $item = $trigger.closest('.faq-item');
            const $list = $item.closest('.faqs-list');
            const isCurrentlyActive = $item.hasClass('is-active');

            // Close sibling items in the same container for clean accordion UX
            if ($list.length) {
                $list.find('.faq-item.is-active').not($item).each(function () {
                    $(this).removeClass('is-active');
                    $(this).find('.faq-item__question').attr('aria-expanded', 'false');
                });
            }

            // Toggle current item
            if (isCurrentlyActive) {
                $item.removeClass('is-active');
                $trigger.attr('aria-expanded', 'false');
            } else {
                $item.addClass('is-active');
                $trigger.attr('aria-expanded', 'true');
            }
        });

        // Keyboard accessibility for non-button triggers (Enter and Space)
        $(document).on('keydown', '.faq-item__question[role="button"], div.faq-item__question', function (e) {
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                $(this).trigger('click');
            }
        });
    };

    /**
     * Single Tour Gallery Lightbox (Fancybox 5 integration)
     * Handles keyboard navigation (Escape, arrows), swipe gestures, zoom,
     * infobar counter, and mobile CTA trigger.
     */
    const initTourGalleryLightbox = () => {
        if (typeof window.Fancybox !== 'undefined') {
            window.Fancybox.bind('[data-fancybox="tour-gallery"]', {
                dragToClose: true,
                closeButton: 'top',
                Images: {
                    zoom: true,
                },
                Toolbar: {
                    display: {
                        left: ['infobar'],
                        middle: [],
                        right: ['iterateZoom', 'slideshow', 'fullscreen', 'close'],
                    },
                },
                Thumbs: false,
                keyboard: {
                    Escape: 'close',
                    Delete: 'close',
                    Backspace: 'close',
                    PageUp: 'next',
                    PageDown: 'prev',
                    ArrowUp: 'next',
                    ArrowDown: 'prev',
                    ArrowRight: 'next',
                    ArrowLeft: 'prev',
                },
            });
        }

        // Delegate mobile CTA or other custom buttons to open gallery album
        $(document).on('click', '[data-gallery-trigger]', function (e) {
            e.preventDefault();
            const firstGalleryItem = document.querySelector('[data-fancybox="tour-gallery"]');
            if (firstGalleryItem) {
                firstGalleryItem.click();
            }
        });
    };

    $(document).ready(function () {
        modalSearch();
        heroSlider();
        destinationPreview();
        tourSwiper();
        initFaqAccordion();
        initTourGalleryLightbox();
    });
})(jQuery);
