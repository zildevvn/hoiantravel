<?php
$heading = get_field('hd_list_tours') ?? '';
$desc = get_field('desc_list_tours') ?? '';
$badge = get_field('badge_list_tours') ?? '';
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 2,
    'post_status' => 'publish',
);

$query = new WP_Query($args);
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// echo "<pre>";
// echo print_r($query->max_num_pages);
// echo "</pre>";
?>
<?php if ($query->have_posts()): ?>
    <section class="tours-list-section ha-section" id="tours-list-section">
        <div class="container">

            <!-- Section Intro -->
            <div class="tours-list-intro text-center">
                <?php if ($badge): ?>
                    <span class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= esc_html($badge); ?>
                    </span>
                <?php endif; ?>
                <?php if ($heading): ?>
                    <h2 class="ha-heading tours-list-intro__title">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($desc): ?>
                    <p class="ha-sub-heading tours-list-intro__desc">
                        <?= esc_html($desc); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- 1. Horizontal Interactive Category Tabs -->
            <?php
            $tour_cats = get_terms(array(
                'taxonomy' => 'tour_cats',
                'hide_empty' => true,
            ));
            ?>
            <div class="tours-category-nav-wrapper">
                <div class="tours-category-nav" role="tablist" aria-label="Tour categories">
                    <label class="tours-cat-pill is-active">
                        <input type="radio" name="tour_cat" value="all" checked class="d-none">
                        <span class="tours-cat-pill__icon" aria-hidden="true"><?= ha_svg_icon('tour-package'); ?></span>
                        <span class="tours-cat-pill__name">All Tours</span>
                        <span class="tours-cat-pill__count"><?= esc_html($query->found_posts); ?></span>
                    </label>
                    <?php if (!empty($tour_cats) && !is_wp_error($tour_cats)): ?>
                        <?php foreach ($tour_cats as $cat): ?>
                            <label class="tours-cat-pill" data-slug="<?php echo esc_attr($cat->slug); ?>">
                                <input type="radio" name="tour_cat" value="<?php echo esc_attr($cat->term_id); ?>"
                                    data-slug="<?php echo esc_attr($cat->slug); ?>" class="d-none">
                                <span class="tours-cat-pill__name"><?php echo esc_html($cat->name); ?></span>
                                <?php if ($cat->count > 0): ?>
                                    <span class="tours-cat-pill__count"><?php echo esc_html($cat->count); ?></span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 2. Master Filter & Search Toolbar (Direct on Desktop/Tablet, Compact + Drawer on Mobile) -->
            <div class="tours-toolbar">
                <!-- Zone 1: Search Box -->
                <div class="tours-toolbar__group tours-toolbar__group--search">
                    <label for="ha-tours-search-input" class="tours-toolbar__label">
                        <?= ha_svg_icon('search-icon'); ?>
                        <span>Search Tours</span>
                    </label>
                    <div class="tours-toolbar__search">
                        <span class="tours-toolbar__search-icon" aria-hidden="true">
                            <?= ha_svg_icon('search-icon'); ?>
                        </span>
                        <input type="text" id="ha-tours-search-input" placeholder="Search experiences, destinations..."
                            class="tours-toolbar__search-input" autocomplete="off">
                        <button type="button" class="tours-toolbar__search-clear" id="ha-search-clear-btn"
                            aria-label="Clear search" style="display: none;">
                            <?= ha_svg_icon('menu-close-icon'); ?>
                        </button>
                    </div>
                </div>

                <!-- Zone 2 & 3: Pax & Price Filters (Inline on Desktop/Tablet, Off-Canvas Drawer on Mobile) -->
                <div class="tours-filter-drawer" id="ha-tours-filter-drawer" aria-hidden="false">
                    <div class="tours-filter-drawer__header">
                        <div class="tours-filter-drawer__title-wrap">
                            <?= ha_svg_icon('filter-icon'); ?>
                            <h3 class="tours-filter-drawer__title">Filter Options</h3>
                        </div>
                        <button type="button" class="btn-close-filter" id="ha-close-filter-btn" aria-label="Close filters">
                            <?= ha_svg_icon('menu-close-icon'); ?>
                        </button>
                    </div>

                    <div class="tours-filter-drawer__body">
                        <!-- Pax Widget -->
                        <div class="tours-drawer-widget tours-toolbar__group tours-toolbar__group--pax">
                            <div class="range-header">
                                <label class="form-label tours-toolbar__label">
                                    <?= ha_svg_icon('users'); ?>
                                    <span>Guests (Pax)</span>
                                </label>
                                <span class="range-value" id="ha-tours-pax-display">1 - 20 Guests</span>
                            </div>
                            <div class="range-slider-container">
                                <div id="ha-tours-pax-slider"></div>
                                <div class="range-slider-Result-hints" aria-hidden="true">
                                    <span>1 guest</span>
                                    <span>50 guests</span>
                                </div>
                            </div>
                            <input type="hidden" id="ha-tours-pax-min" value="1" data-default="1">
                            <input type="hidden" id="ha-tours-pax-max" value="50" data-default="20">
                        </div>

                        <!-- Price Widget -->
                        <div class="tours-drawer-widget tours-toolbar__group tours-toolbar__group--price">
                            <div class="range-header">
                                <label class="form-label tours-toolbar__label">
                                    <span>Price Range</span>
                                </label>
                                <span class="range-value"
                                    id="ha-tours-price-display"><?= esc_html(ha_format_price(0, $currency)); ?> -
                                    <?= esc_html(ha_format_price(500, $currency)); ?></span>
                            </div>
                            <div class="range-slider-container">
                                <div id="ha-tours-price-slider" data-min="0" data-max="500" data-step="10"></div>
                                <div class="range-slider-Result-hints" aria-hidden="true">
                                    <span><?= esc_html(ha_format_price(0, $currency)); ?></span>
                                    <span><?= esc_html(ha_format_price(500, $currency)); ?></span>
                                </div>
                            </div>
                            <input type="hidden" id="ha-tours-price-min" value="0" min="0" max="500" step="10" data-default="0">
                            <input type="hidden" id="ha-tours-price-max" value="500" min="0" max="500" step="10" data-default="500">
                        </div>
                    </div>

                    <div class="tours-filter-drawer__footer">
                        <button type="button" class="tours-drawer-btn-reset" id="ha-drawer-reset-btn">
                            <?= ha_svg_icon('rotate-ccw'); ?>
                            Reset
                        </button>
                        <button type="button" class="tours-drawer-btn-apply ha-button" id="ha-drawer-apply-btn">
                            Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Zone 4: Right Toolbar Actions (Mobile Filter Trigger + Sort Select) -->
                <div class="tours-toolbar__group tours-toolbar__group--actions tours-toolbar__actions">
                    <!-- Filter Trigger Button (Mobile Only - Opens Drawer) -->
                    <button type="button" class="tours-toolbar__btn-filter" id="ha-mobile-filter-btn"
                        aria-label="Open filter options" aria-expanded="false" aria-controls="ha-tours-filter-drawer">
                        <span class="tours-toolbar__btn-icon" aria-hidden="true"><?= ha_svg_icon('filter-icon'); ?></span>
                        <span class="tours-toolbar__btn-text">Filter Options</span>
                        <span class="filter-count-badge" id="ha-filter-active-count" style="display: none;">0</span>
                    </button>

                    <!-- Sort Select -->
                    <div class="tours-toolbar__sort-wrap">
                        <label for="ha-tours-sort" class="tours-toolbar__label">
                            <span>Sort By</span>
                        </label>
                        <div class="tours-toolbar__sort">
                            <select id="ha-tours-sort" class="tours-toolbar__select">
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
            <div class="tours-results-bar">
                <div class="tours-results-meta">
                    <div class="tours-result-count">
                        Showing <strong id="ha-tours-count"><?= esc_html($query->found_posts); ?></strong> tours in Hoi An
                    </div>
                </div>

                <!-- Active Filter Chips Bar -->
                <div class="tours-active-chips" id="ha-active-chips" style="display: none;">
                    <div class="tours-active-chips__list" id="ha-active-chips-list"></div>
                    <button type="button" class="tours-active-chips__clear-all" id="ha-chips-clear-all">Clear all</button>
                </div>

                <!-- Clear All Filters Button -->
                <button type="button" id="ha-clear-filters" class="tours-toolbar__btn-clear" style="display: none;">
                    <?= ha_svg_icon('rotate-ccw'); ?>
                    <span>Reset Filters</span>
                </button>
            </div>

            <!-- 3. Full-Width Main Tour Content -->
            <div class="tours-content position-relative">
                <!-- Loading Overlay -->
                <div class="ha-loading-overlay">
                    <div class="ha-loading-spinner">
                        <div class="spinner-dot"></div>
                    </div>
                </div>

                <!-- Tour Results Grid (3 Columns) -->
                <div id="ha-tours-results" class="tours-grid" data-query='<?= json_encode($args) ?>' data-currentpage="1">
                    <?php while ($query->have_posts()):
                        $query->the_post();
                        ha_item_tour();
                    endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

                <!-- Empty State -->
                <div id="ha-tours-empty" class="tours-empty-state" style="display: none;">
                    <div class="tours-empty-state__icon">
                        <?= ha_svg_icon('feature-compass'); ?>
                    </div>
                    <h3 class="tours-empty-state__title">No matching tours found</h3>
                    <p class="tours-empty-state__desc">Try adjusting your filters, clearing your search keywords, or
                        selecting "All Tours".</p>
                    <button type="button" class="ha-button ha-button--outline tours-empty-state__btn"
                        id="ha-empty-reset-btn">
                        <?= ha_svg_icon('rotate-ccw'); ?>
                        <span>Reset All Filters</span>
                    </button>
                </div>

                <?php if ($query->max_num_pages > 1): ?>
                    <!-- Pagination -->
                    <div id="ha-tours-pagination" class="tours-pagination-wrapper" data-ajax="true"
                        data-action="ha_ajax_filter_tours" data-container="#ha-tours-results"
                        data-nonce="<?= esc_attr(wp_create_nonce('ha_filter_tours')) ?>"
                        data-params="<?= esc_attr(wp_json_encode([])) ?>">
                        <?php ha_pagination($paged, $query->max_num_pages); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tours-sidebar-backdrop" id="ha-tours-sidebar-backdrop"></div>

        </div>
    </section>
<?php endif; ?>