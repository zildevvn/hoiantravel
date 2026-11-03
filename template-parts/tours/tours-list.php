<?php
$heading = get_field('hd_list_tours') ?? '';
$desc = get_field('desc_list_tours') ?? '';
$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 12,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'tour_cats',
            'field' => 'slug',
            'terms' => array('car-tours'),
            'operator' => 'NOT IN',
        ),
    ),
);

$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="tours-list-section vm-section">
        <div class="container">
            <?php vm_icon_heading() ?>
            <?php if ($heading): ?>
                <h2 class="vm-heading">
                    <?= $heading ?>
                </h2>
            <?php endif; ?>
            <?php if ($desc): ?>
                <p class="vm-sub-heading">
                    <?= $desc ?>
                </p>
            <?php endif; ?>

            <div class="tours-list-wrapper">
                <div class="tours-sidebar-backdrop" id="vm-tours-sidebar-backdrop"></div>
                <div class="tours-sidebar">
                    <div class="tours-sidebar__header mobile-only">
                        <h3 class="tours-sidebar__title">Filters</h3>
                        <button type="button" class="btn-close-filter" id="vm-close-filter-btn" aria-label="Close filters">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="tours-sidebar__widget tours-sidebar__search">
                        <div class="search-input-wrapper">
                            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                            <input type="text" id="vm-tours-search-input" placeholder="Search tours..."
                                class="form-control">
                        </div>
                    </div>
                    <div class="tours-sidebar__widget tours-sidebar__pax">
                        <div class="range-header">
                            <label class="form-label">Number of Pax</label>
                            <span class="range-value" id="vm-tours-pax-display">1 - 20 Guests</span>
                        </div>
                        <div class="range-slider-container">
                            <div id="vm-tours-pax-slider"></div>
                        </div>
                        <input type="hidden" id="vm-tours-pax-min" value="1">
                        <input type="hidden" id="vm-tours-pax-max" value="50">
                    </div>

                    <div class="tours-sidebar__widget tours-sidebar__price">
                        <div class="range-header">
                            <label class="form-label">Price Range</label>
                            <span class="range-value" id="vm-tours-price-display">₫0 - ₫20,000,000</span>
                        </div>
                        <div class="range-slider-container">
                            <div id="vm-tours-price-slider"></div>
                        </div>
                        <input type="hidden" id="vm-tours-price-min" value="0">
                        <input type="hidden" id="vm-tours-price-max" value="20000000">
                    </div>

                    <?php
                    $tour_cats = get_terms(array(
                        'taxonomy' => 'tour_cats',
                        'hide_empty' => true,
                    ));
                    ?>
                    <div class="tours-sidebar__widget tours-sidebar__cats">
                        <h4 class="form-label">Categories</h4>
                        <div class="tours-category-list">
                            <label class="tours-category-item">
                                <input type="radio" name="tour_cat" value="all" checked>
                                <span class="custom-radio"></span>
                                <span class="tours-category-name">All Tours</span>
                            </label>
                            <?php if (!empty($tour_cats) && !is_wp_error($tour_cats)): ?>
                                <?php foreach ($tour_cats as $cat): ?>
                                    <label class="tours-category-item <?php echo esc_attr($cat->slug); ?>">
                                        <input type="radio" name="tour_cat" value="<?php echo esc_attr($cat->term_id); ?>"
                                            data-slug="<?php echo esc_attr($cat->slug); ?>">
                                        <span class="custom-radio"></span>
                                        <span class="tours-category-name">
                                            <?php echo esc_html($cat->name); ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="tours-content position-relative">
                    <div class="vm-loading-overlay">
                        <div class="vm-loading-spinner">
                            <div class="spinner-dot"></div>
                        </div>
                    </div>

                    <div class="tours-main">
                        <div class="tours-main__header">
                            <div class="tours-result-count">
                                <span id="vm-tours-count"><?= $query->found_posts ?></span> tours found
                            </div>
                            <div class="tours-header-controls">
                                <button type="button" class="btn-mobile-filter" id="vm-mobile-filter-btn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    Filters
                                </button>
                                <button type="button" id="vm-clear-filters" class="btn-clear-filters"
                                    style="display: none;">
                                    Clear Filters
                                </button>
                                <div class="tours-sort">
                                    <select id="vm-tours-sort" class="form-control">
                                        <option value="default">Default Sorting</option>
                                        <option value="price_low">Price: Low to High</option>
                                        <option value="price_high">Price: High to Low</option>
                                        <option value="newest">Newest</option>
                                        <option value="title_az">Title: A–Z</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="vm-tours-results" data-query='<?= json_encode($args) ?>' data-currentpage="1">
                            <?php while ($query->have_posts()):
                                $query->the_post();
                                vm_item_tour();
                                ?>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>

                    <div id="vm-tours-pagination" data-ajax="true" data-action="vm_ajax_filter_tours"
                        data-container="#vm-tours-results" data-nonce="<?= esc_attr(wp_create_nonce('vm_filter_tours')) ?>"
                        data-params="<?= esc_attr(wp_json_encode([])) ?>">
                        <?php vm_pagination($paged, $query->max_num_pages); ?>
                    </div>

                    <div id="vm-tours-empty" style="display: none;">No results found for your search.</div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>