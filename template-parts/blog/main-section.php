<?php
/**
 * Blog Main Section: AJAX Search, Category Filter, and Pagination
 */

$search_query = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';
$raw_cat      = isset($_GET['category']) ? sanitize_text_field(wp_unslash($_GET['category'])) : (isset($_GET['cat']) ? sanitize_text_field(wp_unslash($_GET['cat'])) : '');

$paged = max(1, intval(get_query_var('paged')), intval(get_query_var('page')), intval($_GET['paged'] ?? 0), intval($_GET['page'] ?? 0));

$blog_cats = get_terms(array(
    'taxonomy'   => 'category',
    'hide_empty' => true,
));

$selected_term     = null;
$selected_cat_slug = '';
$selected_cat_name = __('All Categories', 'hoiantravel');

if (!empty($raw_cat) && $raw_cat !== 'all') {
    if (is_numeric($raw_cat)) {
        $found = get_term(intval($raw_cat), 'category');
    } else {
        $found = get_term_by('slug', $raw_cat, 'category');
    }

    if ($found && !is_wp_error($found)) {
        $selected_term     = $found;
        $selected_cat_slug = $found->slug;
        $selected_cat_name = $found->name;
    }
}

$has_active_filters = !empty($search_query) || !empty($selected_cat_slug);

$args = array(
    'post_type'      => 'post',
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
);

if (!empty($search_query)) {
    $args['s'] = $search_query;
}

if ($selected_term) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $selected_term->term_id,
        ),
    );
}

$query = new WP_Query($args);

$heading = get_field('hd_blog_tpl') ?: get_field('heading_cars_home');
$badge   = get_field('badge_blog_tpl') ?: get_field('badge_cars_home');
$desc    = get_field('desc_blog_tpl') ?: get_field('desc_cars_home');
?>

<section class="ha-section posts-list-section" id="posts-list-section" data-nonce="<?= esc_attr(wp_create_nonce('ha_filter_posts')) ?>">
    <div class="container">
        <?php if (!empty($badge) || !empty($heading) || !empty($desc)): ?>
            <div class="posts-list-intro text-center">
                <?php if (!empty($badge)): ?>
                    <div class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <span><?= esc_html($badge); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="ha-heading posts-list-intro__title">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <p class="ha-sub-heading posts-list-intro__desc">
                        <?= wp_kses_post($desc); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Search & Filter Bar (Pure AJAX Container — No Form Element) -->
        <div class="posts-filter-bar" id="ha-posts-filter-bar" role="search" aria-label="<?php esc_attr_e('Filter posts', 'hoiantravel'); ?>">
            <!-- Search Box (Auto Search with Debounce) -->
            <div class="posts-filter-bar__search">
                <div class="search-input-wrapper">
                    <label for="ha-posts-search-input" class="visually-hidden">
                        <?php esc_html_e('Search insights, news, and guides', 'hoiantravel'); ?>
                    </label>
                    <span class="search-icon" aria-hidden="true">
                        <?= ha_svg_icon('search-icon'); ?>
                    </span>
                    <input type="search"
                           name="s"
                           id="ha-posts-search-input"
                           value="<?= esc_attr($search_query); ?>"
                           placeholder="<?php esc_attr_e('Search insights, news, and guides...', 'hoiantravel'); ?>"
                           class="form-control"
                           autocomplete="off">
                    <button type="button" class="search-clear-btn" id="ha-posts-search-clear" aria-label="<?php esc_attr_e('Clear search keyword', 'hoiantravel'); ?>" style="<?= empty($search_query) ? 'display: none;' : '' ?>">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Category Dropdown -->
            <div class="posts-filter-bar__cats">
                <input type="hidden" name="category" id="ha-posts-category-input" value="<?= esc_attr($selected_cat_slug); ?>">
                <div class="vm-custom-dropdown posts-category-dropdown" id="ha-posts-category-dropdown">
                    <button type="button" class="vm-dropdown-trigger" aria-expanded="false"
                        aria-controls="posts-category-menu" id="ha-posts-category-trigger"
                        aria-label="<?php esc_attr_e('Filter by category', 'hoiantravel'); ?>">
                        <span class="vm-dropdown-label" id="ha-posts-category-label"><?= esc_html($selected_cat_name); ?></span>
                        <span class="vm-dropdown-icon">
                            <?= ha_svg_icon('chevron-down'); ?>
                        </span>
                    </button>
                    <div id="posts-category-menu" class="vm-dropdown-menu tours-category-list" role="listbox">
                        <div class="tours-category-item<?= empty($selected_cat_slug) ? ' is-active' : ''; ?>"
                             data-value=""
                             data-label="<?php esc_attr_e('All Categories', 'hoiantravel'); ?>"
                             role="option"
                             tabindex="0">
                            <span class="tours-category-name"><?php esc_html_e('All Categories', 'hoiantravel'); ?></span>
                        </div>
                        <?php if (!empty($blog_cats) && !is_wp_error($blog_cats)): ?>
                            <?php foreach ($blog_cats as $cat): ?>
                                <div class="tours-category-item<?= ($selected_cat_slug === $cat->slug) ? ' is-active' : ''; ?>"
                                     data-value="<?= esc_attr($cat->slug); ?>"
                                     data-label="<?= esc_attr($cat->name); ?>"
                                     role="option"
                                     tabindex="0">
                                    <span class="tours-category-name"><?= esc_html($cat->name); ?></span>
                                    <span class="tours-category-count">(<?= intval($cat->count); ?>)</span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions (Reset Button) -->
            <div class="posts-filter-bar__actions" style="<?= $has_active_filters ? '' : 'display: none;' ?>">
                <button type="button" class="btn-clear-filters" id="ha-posts-clear-filters" style="<?= $has_active_filters ? '' : 'display: none;' ?>" aria-label="<?php esc_attr_e('Reset all filters', 'hoiantravel'); ?>">
                    <?= ha_svg_icon('rotate-ccw'); ?>
                    <span><?php esc_html_e('Reset', 'hoiantravel'); ?></span>
                </button>
            </div>
        </div>

        <!-- Posts Content & Empty State Wrapper -->
        <div class="posts-list-wrapper position-relative">
            <!-- Loading Overlay -->
            <div class="vm-loading-overlay">
                <div class="vm-loading-spinner">
                    <div class="spinner-dot"></div>
                </div>
            </div>

            <div class="posts-main">
                <div class="posts-main__header d-flex justify-content-between align-items-center mb-4">
                    <div class="posts-result-count">
                        <span id="vm-posts-count"><?= intval($query->found_posts); ?></span>
                        <span class="posts-result-text"><?= esc_html(_n('post available', 'posts available', $query->found_posts, 'hoiantravel')); ?></span>
                        <span class="posts-result-query" style="<?= empty($search_query) ? 'display: none;' : '' ?>">
                            <?php if (!empty($search_query)): ?>
                                <?= sprintf(esc_html__('for "%s"', 'hoiantravel'), esc_html($search_query)); ?>
                            <?php endif; ?>
                        </span>
                        <span class="posts-result-category" style="<?= empty($selected_term) ? 'display: none;' : '' ?>">
                            <?php if (!empty($selected_term)): ?>
                                <?= sprintf(esc_html__('in %s', 'hoiantravel'), esc_html($selected_term->name)); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>

                <div id="vm-posts-results" class="post-grid" data-currentpage="<?= esc_attr($paged); ?>" style="<?= $query->have_posts() ? '' : 'display: none;' ?>">
                    <?php if ($query->have_posts()):
                        while ($query->have_posts()):
                            $query->the_post();
                            ha_item_post();
                        endwhile;
                        wp_reset_postdata();
                    endif; ?>
                </div>
            </div>

            <div id="vm-posts-pagination" class="posts-pagination-wrapper" style="<?= $query->have_posts() ? '' : 'display: none;' ?>">
                <?php
                if ($query->have_posts()) {
                    $pagination_args = [];
                    if (!empty($search_query)) {
                        $pagination_args['s'] = $search_query;
                    }
                    if (!empty($selected_cat_slug)) {
                        $pagination_args['category'] = $selected_cat_slug;
                    }
                    ha_pagination($paged, $query->max_num_pages, $pagination_args);
                }
                ?>
            </div>

            <div id="vm-posts-empty" style="<?= $query->have_posts() ? 'display: none;' : '' ?>">
                <div class="empty-state">
                    <div class="empty-state__icon">
                        <?= ha_svg_icon('search-empty'); ?>
                    </div>
                    <h3 class="empty-state__title"><?php esc_html_e('No posts found', 'hoiantravel'); ?></h3>
                    <p class="empty-state__desc">
                        <?php if ($has_active_filters): ?>
                            <?php esc_html_e("We couldn't find any articles matching your search or filter criteria. Try adjusting your search keyword or selecting a different category.", 'hoiantravel'); ?>
                        <?php else: ?>
                            <?php esc_html_e('No articles are currently available. Please check back later.', 'hoiantravel'); ?>
                        <?php endif; ?>
                    </p>
                    <div class="empty-state__action mt-3">
                        <button type="button" class="ha-button ha-button--secondary" id="ha-posts-empty-reset">
                            <?= ha_svg_icon('rotate-ccw'); ?>
                            <span><?php esc_html_e('Reset All Filters', 'hoiantravel'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
