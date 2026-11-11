/**
 * Filter Car Component
 * Scope: #cars-list-section
 * Handles all car filtering, drawer toggles, sliders, and AJAX updates.
 */

(function ($) {
    "use strict";

    const getCurrencySymbol = () => {
        if (window.php_data && window.php_data.currency) {
            return window.php_data.currency;
        }
        return '₫';
    };

    const formatPriceValue = (value) => {
        const num = Math.round(Number(value) || 0);
        return getCurrencySymbol() + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    };

    const initCarFilter = () => {
        const section = document.getElementById('cars-list-section');
        if (!section) return;

        // Core elements
        const resultsContainer = section.querySelector('#ha-cars-results');
        const paginationContainer = section.querySelector('#ha-cars-pagination');
        const countDisplay = section.querySelector('#ha-cars-count');
        const emptyState = section.querySelector('#ha-cars-empty');
        const loadingOverlay = section.querySelector('.ha-loading-overlay');

        // Filter controls
        const searchInput = section.querySelector('#ha-cars-search-input');
        const searchClearBtn = section.querySelector('#ha-cars-search-clear-btn');
        const sortSelect = section.querySelector('#ha-cars-sort');
        const clearFiltersBtn = section.querySelector('#ha-cars-clear-filters');
        const activeChipsContainer = section.querySelector('#ha-cars-active-chips');
        const activeChipsList = section.querySelector('#ha-cars-active-chips-list');
        const chipsClearAllBtn = section.querySelector('#ha-cars-chips-clear-all');
        const emptyResetBtn = section.querySelector('#ha-cars-empty-reset-btn');
        const activeCountBadge = section.querySelector('#ha-cars-filter-active-count');

        // Drawer elements
        const drawer = section.querySelector('#ha-cars-filter-drawer');
        const backdrop = section.querySelector('#ha-cars-sidebar-backdrop');
        const openDrawerBtn = section.querySelector('#ha-cars-mobile-filter-btn');
        const closeDrawerBtn = section.querySelector('#ha-cars-close-filter-btn');
        const drawerApplyBtn = section.querySelector('#ha-cars-drawer-apply-btn');
        const drawerResetBtn = section.querySelector('#ha-cars-drawer-reset-btn');

        // Sliders
        const paxSliderEl = section.querySelector('#ha-cars-pax-slider');
        const paxDisplay = section.querySelector('#ha-cars-pax-display');
        const paxMinInput = section.querySelector('#ha-cars-pax-min');
        const paxMaxInput = section.querySelector('#ha-cars-pax-max');

        const priceSliderEl = section.querySelector('#ha-cars-price-slider');
        const priceDisplay = section.querySelector('#ha-cars-price-display');
        const priceMinInput = section.querySelector('#ha-cars-price-min');
        const priceMaxInput = section.querySelector('#ha-cars-price-max');

        // State tracking
        const DEFAULT_PAX_MIN = paxMinInput ? (Number(paxMinInput.getAttribute('data-default') || paxMinInput.value) || 1) : 1;
        const DEFAULT_PAX_MAX = 50;
        const INITIAL_PAX_MAX = paxMaxInput ? (Number(paxMaxInput.getAttribute('data-default')) || 20) : 20;

        const PRICE_MIN = 0;
        const PRICE_MAX = 500;
        const PRICE_STEP = 10;
        const DEFAULT_PRICE_MIN = PRICE_MIN;
        const DEFAULT_PRICE_MAX = PRICE_MAX;

        const sanitizePriceStep = (val, fallback) => {
            const num = Number(val);
            if (!Number.isFinite(num)) return fallback;
            const stepped = Math.round(num / PRICE_STEP) * PRICE_STEP;
            return Math.min(PRICE_MAX, Math.max(PRICE_MIN, stepped));
        };

        let paxSlider = null;
        let priceSlider = null;
        let searchDebounceTimer = null;
        let sliderDebounceTimer = null;
        let activeXHR = null;

        const isMobileDrawerMode = () => {
            return window.innerWidth < 768;
        };

        const triggerDesktopSliderFilter = () => {
            // On desktop/tablet (inline toolbar), automatically apply filter when slider handle is released
            if (!isMobileDrawerMode() || !(drawer && drawer.classList.contains('is-open'))) {
                clearTimeout(sliderDebounceTimer);
                sliderDebounceTimer = setTimeout(() => {
                    fetchCars(1);
                }, 150);
            }
        };

        // ----------------------------------------------------
        // 1. Initialize noUiSlider (if library is loaded)
        // ----------------------------------------------------
        if (typeof noUiSlider !== 'undefined') {
            if (paxSliderEl && !paxSliderEl.noUiSlider) {
                paxSlider = noUiSlider.create(paxSliderEl, {
                    start: [DEFAULT_PAX_MIN, INITIAL_PAX_MAX],
                    connect: true,
                    step: 1,
                    range: {
                        'min': DEFAULT_PAX_MIN,
                        'max': DEFAULT_PAX_MAX
                    }
                });

                paxSlider.on('update', function (values) {
                    const min = Math.round(values[0]);
                    const max = Math.round(values[1]);
                    if (paxMinInput) paxMinInput.value = min;
                    if (paxMaxInput) paxMaxInput.value = max;
                    if (paxDisplay) {
                        paxDisplay.textContent = `${min} - ${max} Guests`;
                    }
                });

                paxSlider.on('change', function () {
                    triggerDesktopSliderFilter();
                });
            }

            if (priceSliderEl && !priceSliderEl.noUiSlider) {
                priceSlider = noUiSlider.create(priceSliderEl, {
                    start: [DEFAULT_PRICE_MIN, DEFAULT_PRICE_MAX],
                    connect: true,
                    step: PRICE_STEP,
                    range: {
                        'min': DEFAULT_PRICE_MIN,
                        'max': DEFAULT_PRICE_MAX
                    }
                });

                priceSlider.on('update', function (values) {
                    const min = sanitizePriceStep(values[0], DEFAULT_PRICE_MIN);
                    const max = sanitizePriceStep(values[1], DEFAULT_PRICE_MAX);
                    if (priceMinInput) priceMinInput.value = min;
                    if (priceMaxInput) priceMaxInput.value = max;
                    if (priceDisplay) {
                        priceDisplay.textContent = `${formatPriceValue(min)} - ${formatPriceValue(max)}`;
                    }
                });

                priceSlider.on('change', function () {
                    triggerDesktopSliderFilter();
                });
            }
        }

        // ----------------------------------------------------
        // 2. Drawer Open / Close (Mobile Viewports)
        // ----------------------------------------------------
        const syncDrawerA11y = () => {
            if (!drawer) return;
            if (isMobileDrawerMode()) {
                const isOpen = drawer.classList.contains('is-open');
                drawer.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                if (openDrawerBtn) openDrawerBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            } else {
                drawer.classList.remove('is-open');
                drawer.setAttribute('aria-hidden', 'false');
                if (backdrop) backdrop.classList.remove('is-active');
                if (openDrawerBtn) openDrawerBtn.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('cars-filter-open');
            }
        };

        const openDrawer = () => {
            if (drawer) {
                drawer.classList.add('is-open');
                drawer.setAttribute('aria-hidden', 'false');
            }
            if (openDrawerBtn) {
                openDrawerBtn.setAttribute('aria-expanded', 'true');
            }
            if (backdrop) {
                backdrop.classList.add('is-active');
            }
            document.body.classList.add('cars-filter-open');
        };

        const closeDrawer = () => {
            if (drawer) {
                drawer.classList.remove('is-open');
                if (isMobileDrawerMode()) {
                    drawer.setAttribute('aria-hidden', 'true');
                }
            }
            if (openDrawerBtn) {
                openDrawerBtn.setAttribute('aria-expanded', 'false');
            }
            if (backdrop) {
                backdrop.classList.remove('is-active');
            }
            document.body.classList.remove('cars-filter-open');
        };

        window.addEventListener('resize', syncDrawerA11y);
        syncDrawerA11y();

        if (openDrawerBtn) openDrawerBtn.addEventListener('click', openDrawer);
        if (closeDrawerBtn) closeDrawerBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);
        if (drawerApplyBtn) {
            drawerApplyBtn.addEventListener('click', () => {
                closeDrawer();
                fetchCars(1);
            });
        }
        if (drawerResetBtn) {
            drawerResetBtn.addEventListener('click', () => {
                resetSliders();
                fetchCars(1);
            });
        }

        // ----------------------------------------------------
        // 3. Search Input (Debounced)
        // ----------------------------------------------------
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const val = this.value.trim();
                if (searchClearBtn) {
                    searchClearBtn.style.display = val.length > 0 ? 'inline-flex' : 'none';
                }
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    fetchCars(1);
                }, 400);
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchDebounceTimer);
                    fetchCars(1);
                }
            });
        }

        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', function () {
                if (searchInput) {
                    searchInput.value = '';
                    this.style.display = 'none';
                    searchInput.focus();
                }
                fetchCars(1);
            });
        }

        // ----------------------------------------------------
        // 5. Sort Dropdown
        // ----------------------------------------------------
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                fetchCars(1);
            });
        }

        // ----------------------------------------------------
        // 6. Reset Actions
        // ----------------------------------------------------
        const resetSliders = () => {
            if (paxSlider) {
                paxSlider.set([DEFAULT_PAX_MIN, INITIAL_PAX_MAX]);
            }
            if (priceSlider) {
                priceSlider.set([DEFAULT_PRICE_MIN, DEFAULT_PRICE_MAX]);
            }
            if (paxMinInput) paxMinInput.value = DEFAULT_PAX_MIN;
            if (paxMaxInput) paxMaxInput.value = INITIAL_PAX_MAX;
            if (priceMinInput) priceMinInput.value = DEFAULT_PRICE_MIN;
            if (priceMaxInput) priceMaxInput.value = DEFAULT_PRICE_MAX;
        };

        const resetAllFilters = () => {
            if (searchInput) {
                searchInput.value = '';
                if (searchClearBtn) searchClearBtn.style.display = 'none';
            }
            if (sortSelect) {
                sortSelect.value = 'default';
            }
            resetSliders();
            closeDrawer();
            fetchCars(1);
        };

        if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', resetAllFilters);
        if (chipsClearAllBtn) chipsClearAllBtn.addEventListener('click', resetAllFilters);
        if (emptyResetBtn) emptyResetBtn.addEventListener('click', resetAllFilters);

        // ----------------------------------------------------
        // 7. Active Filter Chips Display
        // ----------------------------------------------------
        const updateActiveChips = () => {
            if (!activeChipsList || !activeChipsContainer) return;

            let chipsHtml = '';
            let activeFilterCount = 0;

            // Search Keyword
            const query = searchInput ? searchInput.value.trim() : '';
            if (query) {
                activeFilterCount++;
                chipsHtml += `<span class="cars-chip" data-chip="search">Keyword: "${query}" <button type="button" aria-label="Remove search filter" class="cars-chip__remove">×</button></span>`;
            }

            // Price
            const curPriceMin = priceMinInput ? sanitizePriceStep(priceMinInput.value, DEFAULT_PRICE_MIN) : DEFAULT_PRICE_MIN;
            const curPriceMax = priceMaxInput ? sanitizePriceStep(priceMaxInput.value, DEFAULT_PRICE_MAX) : DEFAULT_PRICE_MAX;
            if (curPriceMin > DEFAULT_PRICE_MIN || curPriceMax < DEFAULT_PRICE_MAX) {
                activeFilterCount++;
                chipsHtml += `<span class="cars-chip" data-chip="price">${formatPriceValue(curPriceMin)} - ${formatPriceValue(curPriceMax)} <button type="button" aria-label="Remove price filter" class="cars-chip__remove">×</button></span>`;
            }

            // Pax
            const curPaxMin = paxMinInput ? Number(paxMinInput.value) : DEFAULT_PAX_MIN;
            const curPaxMax = paxMaxInput ? Number(paxMaxInput.value) : INITIAL_PAX_MAX;
            if (curPaxMin !== DEFAULT_PAX_MIN || curPaxMax !== INITIAL_PAX_MAX) {
                activeFilterCount++;
                chipsHtml += `<span class="cars-chip" data-chip="pax">${curPaxMin} - ${curPaxMax} Guests <button type="button" aria-label="Remove guests filter" class="cars-chip__remove">×</button></span>`;
            }

            activeChipsList.innerHTML = chipsHtml;
            activeChipsContainer.style.display = activeFilterCount > 0 ? 'flex' : 'none';

            if (clearFiltersBtn) {
                clearFiltersBtn.style.display = activeFilterCount > 0 ? 'inline-flex' : 'none';
            }

            if (activeCountBadge) {
                if (activeFilterCount > 0) {
                    activeCountBadge.textContent = activeFilterCount;
                    activeCountBadge.style.display = 'inline-flex';
                } else {
                    activeCountBadge.style.display = 'none';
                }
            }

            // Bind chip removal clicks
            activeChipsList.querySelectorAll('.cars-chip__remove').forEach((btn) => {
                btn.addEventListener('click', function () {
                    const chip = this.closest('.cars-chip');
                    const chipType = chip.getAttribute('data-chip');

                    if (chipType === 'search') {
                        if (searchInput) {
                            searchInput.value = '';
                            if (searchClearBtn) searchClearBtn.style.display = 'none';
                        }
                    } else if (chipType === 'price') {
                        if (priceSlider) priceSlider.set([DEFAULT_PRICE_MIN, DEFAULT_PRICE_MAX]);
                        if (priceMinInput) priceMinInput.value = DEFAULT_PRICE_MIN;
                        if (priceMaxInput) priceMaxInput.value = DEFAULT_PRICE_MAX;
                    } else if (chipType === 'pax') {
                        if (paxSlider) paxSlider.set([DEFAULT_PAX_MIN, INITIAL_PAX_MAX]);
                        if (paxMinInput) paxMinInput.value = DEFAULT_PAX_MIN;
                        if (paxMaxInput) paxMaxInput.value = INITIAL_PAX_MAX;
                    }
                    fetchCars(1);
                });
            });
        };

        // ----------------------------------------------------
        // 8. AJAX Car Fetcher
        // ----------------------------------------------------
        const fetchCars = (page = 1, shouldScroll = false) => {
            const ajaxUrl = (typeof php_data !== 'undefined' && php_data.ajax_url)
                ? php_data.ajax_url
                : '/wp-admin/admin-ajax.php';

            const nonce = paginationContainer
                ? paginationContainer.getAttribute('data-nonce')
                : '';

            const s = searchInput ? searchInput.value.trim() : '';
            const sort = sortSelect ? sortSelect.value : 'default';
            const paxMin = paxMinInput ? paxMinInput.value : 1;
            const paxMax = paxMaxInput ? paxMaxInput.value : 50;
            const priceMin = priceMinInput ? sanitizePriceStep(priceMinInput.value, DEFAULT_PRICE_MIN) : DEFAULT_PRICE_MIN;
            const priceMax = priceMaxInput ? sanitizePriceStep(priceMaxInput.value, DEFAULT_PRICE_MAX) : DEFAULT_PRICE_MAX;

            if (loadingOverlay) loadingOverlay.classList.add('is-active');

            // Abort ongoing request
            if (activeXHR && activeXHR.readyState !== 4) {
                activeXHR.abort();
            }

            activeXHR = $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'ha_ajax_filter_cars',
                    nonce: nonce,
                    s: s,
                    car_cat: 'all',
                    pax_min: paxMin,
                    pax_max: paxMax,
                    price_min: priceMin,
                    price_max: priceMax,
                    sort: sort,
                    paged: page
                },
                success: function (res) {
                    if (loadingOverlay) loadingOverlay.classList.remove('is-active');

                    if (res && res.success && res.data) {
                        const data = res.data;

                        if (resultsContainer) {
                            resultsContainer.innerHTML = data.html || '';
                            resultsContainer.setAttribute('data-currentpage', page);
                        }

                        if (countDisplay) {
                            countDisplay.textContent = data.count || 0;
                        }

                        if (paginationContainer) {
                            paginationContainer.innerHTML = data.pagination || '';
                            bindPagination();
                        }

                        if (emptyState) {
                            emptyState.style.display = (data.count === 0) ? 'block' : 'none';
                        }

                        updateActiveChips();

                        if (shouldScroll) {
                            const offset = section.getBoundingClientRect().top + window.pageYOffset - 80;
                            window.scrollTo({ top: offset, behavior: 'smooth' });
                        }
                    }
                },
                error: function (xhr, status) {
                    if (status !== 'abort' && loadingOverlay) {
                        loadingOverlay.classList.remove('is-active');
                    }
                }
            });
        };

        // ----------------------------------------------------
        // 9. Pagination Clicks (Delegated)
        // ----------------------------------------------------
        const bindPagination = () => {
            if (!paginationContainer) return;
            const links = paginationContainer.querySelectorAll('.page-numbers:not(.current):not(.dots)');
            links.forEach((link) => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    let targetPage = 1;
                    const href = this.getAttribute('href');

                    if (this.classList.contains('prev')) {
                        const cur = parseInt(resultsContainer?.getAttribute('data-currentpage') || '1', 10);
                        targetPage = Math.max(1, cur - 1);
                    } else if (this.classList.contains('next')) {
                        const cur = parseInt(resultsContainer?.getAttribute('data-currentpage') || '1', 10);
                        targetPage = cur + 1;
                    } else {
                        const pageNum = parseInt(this.textContent.trim(), 10);
                        if (!isNaN(pageNum)) {
                            targetPage = pageNum;
                        } else if (href) {
                            const match = href.match(/paged=(\d+)/) || href.match(/page\/(\d+)/);
                            if (match) targetPage = parseInt(match[1], 10);
                        }
                    }

                    fetchCars(targetPage, true);
                });
            });
        };

        bindPagination();
    };

    // DOM Ready
    $(document).ready(function () {
        initCarFilter();
    });

})(jQuery);

