/**
 * Blog Filter: UI interactions for search, category dropdown, and form submission
 */
(function ($) {
    'use strict';

    const initBlogFilter = () => {
        const form = document.getElementById('ha-posts-filter-form');
        if (!form) return;

        const dropdown    = document.getElementById('ha-posts-category-dropdown');
        const trigger     = document.getElementById('ha-posts-category-trigger');
        const menu        = document.getElementById('posts-category-menu');
        const catInput    = document.getElementById('ha-posts-category-input');
        const label       = document.getElementById('ha-posts-category-label');
        const searchInput = document.getElementById('ha-posts-search-input');
        const searchClear = document.getElementById('ha-posts-search-clear');

        // 1. Dropdown Toggle & Outside Click
        if (trigger && menu) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const isOpen = trigger.getAttribute('aria-expanded') === 'true';
                trigger.setAttribute('aria-expanded', !isOpen);
                menu.classList.toggle('show', !isOpen);
                menu.classList.toggle('is-open', !isOpen);
            });

            // Close on click outside
            document.addEventListener('click', function (e) {
                if (dropdown && !dropdown.contains(e.target)) {
                    trigger.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show', 'is-open');
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && trigger.getAttribute('aria-expanded') === 'true') {
                    trigger.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show', 'is-open');
                    trigger.focus();
                }
            });

            // Option selection
            const options = menu.querySelectorAll('.tours-category-item');
            options.forEach(option => {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    const val = this.getAttribute('data-value') || '';
                    const optionLabel = this.getAttribute('data-label') || 'All Categories';

                    if (catInput) catInput.value = val;
                    if (label) label.textContent = optionLabel;

                    options.forEach(opt => opt.classList.remove('is-active'));
                    this.classList.add('is-active');

                    trigger.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show', 'is-open');

                    // Auto-submit form when category is changed
                    form.submit();
                });

                // Support Enter / Space on options
                option.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        }

        // 2. Clear Search Button
        if (searchClear && searchInput) {
            searchClear.addEventListener('click', function (e) {
                e.preventDefault();
                searchInput.value = '';
                form.submit();
            });
        }
    };

    $(document).ready(function () {
        initBlogFilter();
    });
})(jQuery);

