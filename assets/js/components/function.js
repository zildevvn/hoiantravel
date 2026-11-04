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

    $(document).ready(function () {
        modalSearch();
    });
})(jQuery);
