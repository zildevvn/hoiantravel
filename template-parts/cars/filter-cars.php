<?php
$heading = get_field('hd_list_cars') ?: '';
$desc = get_field('desc_list_tours_cars') ?: (get_field('desc_list_cars_cars') ?: '');
$badge = get_field('badge_list_tours_cars') ?: (get_field('badge_list_cars_cars') ?: '');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
$args = array(
    'post_type'      => 'cars',
    'posts_per_page' => 9,
    'post_status'    => 'publish',
);

$query = new WP_Query($args);
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>
<?php if ($query->have_posts()): ?>
    <section class="cars-list-section ha-section" id="cars-list-section">
        <div class="container">

            <!-- Section Intro -->
            <div class="cars-list-intro text-center">
                <?php if ($badge): ?>
                    <span class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= esc_html($badge); ?>
                    </span>
                <?php endif; ?>
                <?php if ($heading): ?>
                    <h2 class="ha-heading cars-list-intro__title">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($desc): ?>
                    <p class="ha-sub-heading cars-list-intro__desc">
                        <?= esc_html($desc); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Master Filter & Search Toolbar (Direct on Desktop/Tablet, Compact + Drawer on Mobile) -->
            <div class="cars-toolbar">
                <!-- Zone 1: Search Box -->
                <div class="cars-toolbar__group cars-toolbar__group--search">
                    <label for="ha-cars-search-input" class="cars-toolbar__label">
                        <?= ha_svg_icon('search-icon'); ?>
                        <span>Search Cars</span>
                    </label>
                    <div class="cars-toolbar__search">
                        <span class="cars-toolbar__search-icon" aria-hidden="true">
                            <?= ha_svg_icon('search-icon'); ?>
                        </span>
                        <input type="text" id="ha-cars-search-input" placeholder="Search routes, vehicles, destinations..."
                            class="cars-toolbar__search-input" autocomplete="off">
                        <button type="button" class="cars-toolbar__search-clear" id="ha-cars-search-clear-btn"
                            aria-label="Clear search" style="display: none;">
                            <?= ha_svg_icon('menu-close-icon'); ?>
                        </button>
                    </div>
                </div>

                <!-- Zone 2 & 3: Pax & Price Filters (Inline on Desktop/Tablet, Off-Canvas Drawer on Mobile) -->
                <div class="cars-filter-drawer" id="ha-cars-filter-drawer" aria-hidden="false">
                    <div class="cars-filter-drawer__header">
                        <div class="cars-filter-drawer__title-wrap">
                            <?= ha_svg_icon('filter-icon'); ?>
                            <h3 class="cars-filter-drawer__title">Filter Options</h3>
                        </div>
                        <button type="button" class="btn-close-filter" id="ha-cars-close-filter-btn" aria-label="Close filters">
                            <?= ha_svg_icon('menu-close-icon'); ?>
                        </button>
                    </div>

                    <div class="cars-filter-drawer__body">
                        <!-- Pax Widget -->
                        <div class="cars-drawer-widget cars-toolbar__group cars-toolbar__group--pax">
                            <div class="range-header">
                                <label class="form-label cars-toolbar__label">
                                    <?= ha_svg_icon('users'); ?>
                                    <span>Passengers / Pax</span>
                                </label>
                                <span class="range-value" id="ha-cars-pax-display">1 - 20 Guests</span>
                            </div>
                            <div class="range-slider-container">
                                <div id="ha-cars-pax-slider"></div>
                                <div class="range-slider-Result-hints" aria-hidden="true">
                                    <span>1 guest</span>
                                    <span>50 guests</span>
                                </div>
                            </div>
                            <input type="hidden" id="ha-cars-pax-min" value="1" data-default="1">
                            <input type="hidden" id="ha-cars-pax-max" value="50" data-default="20">
                        </div>

                        <!-- Price Widget -->
                        <div class="cars-drawer-widget cars-toolbar__group cars-toolbar__group--price">
                            <div class="range-header">
                                <label class="form-label cars-toolbar__label">
                                    <span>Price Range</span>
                                </label>
                                <span class="range-value" id="ha-cars-price-display">
                                    <?= esc_html(ha_format_price(0, $currency)); ?> -
                                    <?= esc_html(ha_format_price(500, $currency)); ?>
                                </span>
                            </div>
                            <div class="range-slider-container">
                                <div id="ha-cars-price-slider" data-min="0" data-max="500" data-step="10"></div>
                                <div class="range-slider-Result-hints" aria-hidden="true">
                                    <span>
                                        <?= esc_html(ha_format_price(0, $currency)); ?>
                                    </span>
                                    <span>
                                        <?= esc_html(ha_format_price(500, $currency)); ?>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" id="ha-cars-price-min" value="0" min="0" max="500" step="10"
                                data-default="0">
                            <input type="hidden" id="ha-cars-price-max" value="500" min="0" max="500" step="10"
                                data-default="500">
                        </div>
                    </div>

                    <div class="cars-filter-drawer__footer">
                        <button type="button" class="cars-drawer-btn-reset" id="ha-cars-drawer-reset-btn">
                            <?= ha_svg_icon('rotate-ccw'); ?>
                            Reset
                        </button>
                        <button type="button" class="cars-drawer-btn-apply ha-button" id="ha-cars-drawer-apply-btn">
                            Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Zone 4: Right Toolbar Actions (Mobile Filter Trigger + Sort Select) -->
                <div class="cars-toolbar__group cars-toolbar__group--actions cars-toolbar__actions">
                    <!-- Filter Trigger Button (Mobile Only - Opens Drawer) -->
                    <button type="button" class="cars-toolbar__btn-filter" id="ha-cars-mobile-filter-btn"
                        aria-label="Open filter options" aria-expanded="false" aria-controls="ha-cars-filter-drawer">
                        <span class="cars-toolbar__btn-icon" aria-hidden="true">
                            <?= ha_svg_icon('filter-icon'); ?>
                        </span>
                        <span class="cars-toolbar__btn-text">Filter Options</span>
                        <span class="filter-count-badge" id="ha-cars-filter-active-count" style="display: none;">0</span>
                    </button>

                    <!-- Sort Select -->
                    <div class="cars-toolbar__sort-wrap">
                        <label for="ha-cars-sort" class="cars-toolbar__label">
                            <span>Sort By</span>
                        </label>
                        <div class="cars-toolbar__sort">
                            <select id="ha-cars-sort" class="cars-toolbar__select">
                                <option value="default">Recommended</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="newest">Newest First</option>
                                <option value="title_az">Title: A–Z</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Meta & Active Filter Chips Bar -->
            <div class="cars-results-bar">
                <div class="cars-results-meta">
                    <div class="cars-result-count">
                        Showing <strong id="ha-cars-count">
                            <?= esc_html($query->found_posts); ?>
                        </strong> cars in Hoi An
                    </div>
                </div>

                <!-- Active Filter Chips Bar -->
                <div class="cars-active-chips" id="ha-cars-active-chips" style="display: none;">
                    <div class="cars-active-chips__list" id="ha-cars-active-chips-list"></div>
                    <button type="button" class="cars-active-chips__clear-all" id="ha-cars-chips-clear-all">Clear all</button>
                </div>

                <!-- Clear All Filters Button -->
                <button type="button" id="ha-cars-clear-filters" class="cars-toolbar__btn-clear" style="display: none;">
                    <?= ha_svg_icon('rotate-ccw'); ?>
                    <span>Reset Filters</span>
                </button>
            </div>

            <!-- 3. Full-Width Main Cars Content -->
            <div class="cars-content position-relative">
                <!-- Loading Overlay -->
                <div class="ha-loading-overlay">
                    <div class="ha-loading-spinner">
                        <div class="spinner-dot"></div>
                    </div>
                </div>

                <!-- Cars Results Grid (3 Columns) -->
                <div id="ha-cars-results" class="cars-grid" data-query='<?= json_encode($args) ?>' data-currentpage="1">
                    <?php while ($query->have_posts()):
                        $query->the_post();
                        ha_item_car();
                    endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

                <!-- Empty State -->
                <div id="ha-cars-empty" class="cars-empty-state" style="display: none;">
                    <div class="cars-empty-state__icon">
                        <?= ha_svg_icon('car'); ?>
                    </div>
                    <h3 class="cars-empty-state__title">No matching cars found</h3>
                    <p class="cars-empty-state__desc">Try adjusting your filters, clearing your search keywords, or
                        selecting "All Cars".</p>
                    <button type="button" class="ha-button ha-button--outline cars-empty-state__btn"
                        id="ha-cars-empty-reset-btn">
                        <?= ha_svg_icon('rotate-ccw'); ?>
                        <span>Reset All Filters</span>
                    </button>
                </div>

                <?php if ($query->max_num_pages > 1): ?>
                    <!-- Pagination -->
                    <div id="ha-cars-pagination" class="cars-pagination-wrapper" data-ajax="true"
                        data-action="ha_ajax_filter_cars" data-container="#ha-cars-results"
                        data-nonce="<?= esc_attr(wp_create_nonce('ha_filter_cars')) ?>"
                        data-params="<?= esc_attr(wp_json_encode([])) ?>">
                        <?php ha_pagination($paged, $query->max_num_pages); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="cars-sidebar-backdrop" id="ha-cars-sidebar-backdrop"></div>

        </div>
    </section>
<?php endif; ?>
