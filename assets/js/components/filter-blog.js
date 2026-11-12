/**
 * Blog Filter: Live AJAX Search, Category Filter, and AJAX Pagination
 * Scope: #posts-list-section
 */
(function ($) {
    'use strict';

    const initBlogFilter = () => {
        const section = document.getElementById('posts-list-section');
        if (!section) return;

        // Core containers & elements (pure AJAX, no <form> element required)
        const filterBar          = section.querySelector('#ha-posts-filter-bar');
        const resultsContainer   = section.querySelector('#vm-posts-results');
        const paginationContainer= section.querySelector('#vm-posts-pagination');
        const emptyState         = section.querySelector('#vm-posts-empty');
        const loadingOverlay     = section.querySelector('.vm-loading-overlay');
        const countDisplay       = section.querySelector('#vm-posts-count');
        const queryDisplay       = section.querySelector('.posts-result-query');
        const catDisplay         = section.querySelector('.posts-result-category');
        const countText          = section.querySelector('.posts-result-text');

        // Form controls
        const searchInput        = section.querySelector('#ha-posts-search-input');
        const searchClear        = section.querySelector('#ha-posts-search-clear');
        const dropdown           = section.querySelector('#ha-posts-category-dropdown');
        const trigger            = section.querySelector('#ha-posts-category-trigger');
        const menu               = section.querySelector('#posts-category-menu');
        const catInput           = section.querySelector('#ha-posts-category-input');
        const label              = section.querySelector('#ha-posts-category-label');
        const clearBtn           = section.querySelector('#ha-posts-clear-filters');
        const emptyResetBtn      = section.querySelector('#ha-posts-empty-reset');

        const nonce   = section.getAttribute('data-nonce') || '';
        const ajaxUrl = (window.php_data && window.php_data.ajax_url)
            ? window.php_data.ajax_url
            : '/wp-admin/admin-ajax.php';

        // State tracking
        const state = {
            s: searchInput ? searchInput.value.trim() : '',
            category: catInput ? catInput.value.trim() : '',
            page: parseInt(resultsContainer?.getAttribute('data-currentpage') || '1', 10) || 1,
        };

        let searchDebounceTimer = null;
        let activeXHR           = null;

        // ----------------------------------------------------
        // 1. Sync URL Query Parameters (replaceState)
        // ----------------------------------------------------
        const updateUrlState = () => {
            if (!window.history || !window.history.replaceState) return;
            const url = new URL(window.location.href);

            if (state.s) {
                url.searchParams.set('s', state.s);
            } else {
                url.searchParams.delete('s');
            }

            if (state.category && state.category !== 'all') {
                url.searchParams.set('category', state.category);
            } else {
                url.searchParams.delete('category');
            }

            if (state.page > 1) {
                url.searchParams.set('paged', state.page);
            } else {
                url.searchParams.delete('paged');
                url.searchParams.delete('page');
            }

            window.history.replaceState({}, '', url.toString());
        };

        // ----------------------------------------------------
        // 2. Update Reset Button Visibility
        // ----------------------------------------------------
        const updateResetButtonVisibility = () => {
            const hasActive = Boolean(state.s || (state.category && state.category !== 'all'));
            if (clearBtn) {
                clearBtn.style.display = hasActive ? 'inline-flex' : 'none';
                const actionsWrapper = clearBtn.closest('.posts-filter-bar__actions');
                if (actionsWrapper) {
                    actionsWrapper.style.display = hasActive ? 'flex' : 'none';
                }
            }
        };

        // ----------------------------------------------------
        // 3. Update Header Count Text & Context
        // ----------------------------------------------------
        const updateCountContext = (count, categoryName) => {
            if (countText) {
                countText.textContent = count === 1 ? 'post available' : 'posts available';
            }

            if (queryDisplay) {
                if (state.s) {
                    queryDisplay.textContent = `for "${state.s}"`;
                    queryDisplay.style.display = 'inline';
                } else {
                    queryDisplay.textContent = '';
                    queryDisplay.style.display = 'none';
                }
            }

            if (catDisplay) {
                if (categoryName && state.category && state.category !== 'all') {
                    catDisplay.textContent = `in ${categoryName}`;
                    catDisplay.style.display = 'inline';
                } else {
                    catDisplay.textContent = '';
                    catDisplay.style.display = 'none';
                }
            }
        };

        // ----------------------------------------------------
        // 4. AJAX Fetch Function
        // ----------------------------------------------------
        const fetchPosts = (page = 1, shouldScroll = false) => {
            state.page = page;

            // Abort previous in-flight request to prevent race conditions
            if (activeXHR && typeof activeXHR.abort === 'function') {
                activeXHR.abort();
            }

            // Show loading indicator
            if (loadingOverlay) loadingOverlay.classList.add('is-active');

            // Update URL parameters
            updateUrlState();

            activeXHR = $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ha_ajax_filter_posts',
                    nonce: nonce,
                    s: state.s,
                    category: state.category,
                    paged: state.page,
                },
                success: function (res) {
                    if (loadingOverlay) loadingOverlay.classList.remove('is-active');

                    if (res && res.success && res.data) {
                        const data  = res.data;
                        const count = parseInt(data.count, 10) || 0;

                        // 1. Update Results Grid
                        if (resultsContainer) {
                            resultsContainer.innerHTML = data.html || '';
                            resultsContainer.setAttribute('data-currentpage', page);
                        }

                        // 2. Update Count
                        if (countDisplay) {
                            countDisplay.textContent = count;
                        }
                        updateCountContext(count, data.category_name);

                        // 3. Update Pagination
                        if (paginationContainer) {
                            paginationContainer.innerHTML = data.pagination || '';
                            bindPagination();
                        }

                        // 4. Toggle Empty State vs Results
                        if (count === 0) {
                            if (resultsContainer) resultsContainer.style.display = 'none';
                            if (paginationContainer) paginationContainer.style.display = 'none';
                            if (emptyState) emptyState.style.display = 'block';
                        } else {
                            if (resultsContainer) resultsContainer.style.display = 'grid';
                            if (paginationContainer) paginationContainer.style.display = 'block';
                            if (emptyState) emptyState.style.display = 'none';
                        }

                        updateResetButtonVisibility();

                        // 5. Smooth scroll if requested (e.g. on pagination navigation)
                        if (shouldScroll) {
                            const offset = section.getBoundingClientRect().top + window.pageYOffset - 90;
                            window.scrollTo({ top: offset, behavior: 'smooth' });
                        }
                    }
                },
                error: function (xhr, status) {
                    if (status !== 'abort') {
                        if (loadingOverlay) loadingOverlay.classList.remove('is-active');
                        console.error('AJAX Filter Posts error:', status);
                    }
                }
            });
        };

        // ----------------------------------------------------
        // 5. Live Debounced Search Input
        // ----------------------------------------------------
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const val = this.value.trim();

                if (searchClear) {
                    searchClear.style.display = val.length > 0 ? 'inline-flex' : 'none';
                }

                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    state.s = val;
                    fetchPosts(1, false);
                }, 350);
            });

            // Enter key triggers immediate search without waiting for debounce
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchDebounceTimer);
                    state.s = this.value.trim();
                    fetchPosts(1, false);
                }
            });
        }

        // ----------------------------------------------------
        // 6. Clear Search Keyword Button
        // ----------------------------------------------------
        if (searchClear) {
            searchClear.addEventListener('click', function (e) {
                e.preventDefault();
                if (searchInput) {
                    searchInput.value = '';
                    this.style.display = 'none';
                    searchInput.focus();
                }
                clearTimeout(searchDebounceTimer);
                state.s = '';
                fetchPosts(1, false);
            });
        }

        // ----------------------------------------------------
        // 7. Category Dropdown Interaction
        // ----------------------------------------------------
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

            // Option Selection
            const options = menu.querySelectorAll('.tours-category-item');
            options.forEach(option => {
                option.addEventListener('click', function (e) {
                    e.preventDefault();
                    const val         = this.getAttribute('data-value') || '';
                    const optionLabel = this.getAttribute('data-label') || 'All Categories';

                    if (catInput) catInput.value = val;
                    if (label) label.textContent = optionLabel;

                    options.forEach(opt => opt.classList.remove('is-active'));
                    this.classList.add('is-active');

                    trigger.setAttribute('aria-expanded', 'false');
                    menu.classList.remove('show', 'is-open');

                    state.category = val;
                    fetchPosts(1, false);
                });

                option.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        this.click();
                    }
                });
            });
        }

        // ----------------------------------------------------
        // 8. Reset All Filters Action
        // ----------------------------------------------------
        const resetAllFilters = (e) => {
            if (e) e.preventDefault();

            if (searchInput) {
                searchInput.value = '';
                if (searchClear) searchClear.style.display = 'none';
            }

            if (catInput) catInput.value = '';
            if (label) label.textContent = 'All Categories';

            if (menu) {
                const options = menu.querySelectorAll('.tours-category-item');
                options.forEach(opt => {
                    if (!opt.getAttribute('data-value')) {
                        opt.classList.add('is-active');
                    } else {
                        opt.classList.remove('is-active');
                    }
                });
            }

            state.s = '';
            state.category = '';
            fetchPosts(1, false);
        };

        if (clearBtn) clearBtn.addEventListener('click', resetAllFilters);
        if (emptyResetBtn) emptyResetBtn.addEventListener('click', resetAllFilters);

        // ----------------------------------------------------
        // 9. Delegated Pagination Clicks
        // ----------------------------------------------------
        const bindPagination = () => {
            if (!paginationContainer) return;
            const links = paginationContainer.querySelectorAll('.page-numbers:not(.current):not(.dots)');
            links.forEach((link) => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    let targetPage = 1;
                    const href = this.getAttribute('href') || '';

                    if (this.classList.contains('prev')) {
                        targetPage = Math.max(1, state.page - 1);
                    } else if (this.classList.contains('next')) {
                        targetPage = state.page + 1;
                    } else {
                        const pageNum = parseInt(this.textContent.trim(), 10);
                        if (!isNaN(pageNum)) {
                            targetPage = pageNum;
                        } else if (href) {
                            const match = href.match(/paged=(\d+)/) || href.match(/page\/(\d+)/);
                            if (match) targetPage = parseInt(match[1], 10);
                        }
                    }

                    fetchPosts(targetPage, true);
                });
            });
        };

        // Initialize pagination click events on initial page load
        bindPagination();
    };

    $(document).ready(function () {
        initBlogFilter();
    });
})(jQuery);
