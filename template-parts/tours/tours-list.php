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

            <!-- 2. Master Floating Toolbar -->
            <div class="tours-toolbar">
                <!-- Search Box -->
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

                <!-- Right Toolbar Actions -->
                <div class="tours-toolbar__actions">
                    <!-- Filter Trigger Button (Opens Drawer) -->
                    <button type="button" class="tours-toolbar__btn-filter" id="ha-mobile-filter-btn"
                        aria-label="Open filter options">
                        <span class="tours-toolbar__btn-icon" aria-hidden="true"><?= ha_svg_icon('filter-icon'); ?></span>
                        <span class="tours-toolbar__btn-text">Filter Options</span>
                        <span class="filter-count-badge" id="ha-filter-active-count" style="display: none;">0</span>
                    </button>

                    <!-- Sort Select -->
                    <div class="tours-toolbar__sort">
                        <label for="ha-tours-sort" class="visually-hidden">Sort by</label>
                        <select id="ha-tours-sort" class="tours-toolbar__select">
                            <option value="default">Sort: Recommended</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="newest">Newest First</option>
                            <option value="title_az">Title: A–Z</option>
                        </select>
                    </div>

                    <!-- Clear All Filters Button -->
                    <button type="button" id="ha-clear-filters" class="tours-toolbar__btn-clear" style="display: none;">
                        <?= ha_svg_icon('rotate-ccw'); ?>
                        <span>Reset</span>
                    </button>
                </div>
            </div>

            <!-- Active Filter Chips Bar -->
            <div class="tours-active-chips" id="ha-active-chips" style="display: none;">
                <div class="tours-active-chips__list" id="ha-active-chips-list"></div>
                <button type="button" class="tours-active-chips__clear-all" id="ha-chips-clear-all">Clear all</button>
            </div>

            <!-- Results Meta Bar: Result Count & Quick Status -->
            <div class="tours-results-meta">
                <div class="tours-result-count">
                    Showing <strong id="ha-tours-count"><?= esc_html($query->found_posts); ?></strong> tours in Hoi An
                </div>
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

            <!-- 4. Refined Off-Canvas Filter Drawer (for Pax, Price, & Deep Filtering) -->
            <div class="tours-filter-drawer" id="ha-tours-filter-drawer" aria-hidden="true">
                <div class="tours-filter-drawer__header">
                    <div class="tours-filter-drawer__title-wrap">
                        <?= ha_svg_icon('filter-icon'); ?>
                        <h3 class="tours-filter-drawer__title">Refine Tours</h3>
                    </div>
                    <button type="button" class="btn-close-filter" id="ha-close-filter-btn" aria-label="Close filters">
                        <?= ha_svg_icon('menu-close-icon'); ?>
                    </button>
                </div>

                <div class="tours-filter-drawer__body">
                    <!-- Pax Widget -->
                    <div class="tours-drawer-widget">
                        <div class="range-header">
                            <label class="form-label">Number of Guests</label>
                            <span class="range-value" id="ha-tours-pax-display">1 - 20 Guests</span>
                        </div>
                        <div class="range-slider-container">
                            <div id="ha-tours-pax-slider"></div>
                        </div>
                        <input type="hidden" id="ha-tours-pax-min" value="1">
                        <input type="hidden" id="ha-tours-pax-max" value="50">
                    </div>

                    <!-- Price Widget -->
                    <div class="tours-drawer-widget">
                        <div class="range-header">
                            <label class="form-label">Price Range</label>
                            <span class="range-value"
                                id="ha-tours-price-display"><?= esc_html(ha_format_price(0, $currency)); ?> -
                                <?= esc_html(ha_format_price(500000, $currency)); ?></span>
                        </div>
                        <div class="range-slider-container">
                            <div id="ha-tours-price-slider"></div>
                        </div>
                        <input type="hidden" id="ha-tours-price-min" value="0">
                        <input type="hidden" id="ha-tours-price-max" value="500000">
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
            <div class="tours-sidebar-backdrop" id="ha-tours-sidebar-backdrop"></div>

        </div>
    </section>
<?php endif; ?>