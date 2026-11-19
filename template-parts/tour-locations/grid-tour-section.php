<?php
/**
 * Taxonomy Tour Locations - Grid Tour Section
 *
 * Heritage Editorial + Modern Travel Experience Layout
 * Fully synchronized with Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$term = get_queried_object();
$term_name = ($term instanceof WP_Term && !empty($term->name)) ? $term->name : '';
$term_slug = ($term instanceof WP_Term && !empty($term->slug)) ? $term->slug : '';

$grid_tour = ($term instanceof WP_Term) ? get_field('grid_tour_section', $term) : [];
$heading = $grid_tour['heading'] ?? '';
$sub_heading = $grid_tour['sub_heading'] ?? '';

if (!empty($heading)) {
    $display_heading = !empty($term_name) ? str_ireplace('Gam Ghi Island', $term_name, $heading) : $heading;
} else {
    $display_heading = !empty($term_name)
        ? sprintf(__('Most Popular Tours in %s', 'hoiantravel'), $term_name)
        : __('Most Popular Tours', 'hoiantravel');
}

$display_sub_heading = (!empty($sub_heading) && !empty($term_name))
    ? str_ireplace('Gam Ghi Island', $term_name, $sub_heading)
    : $sub_heading;

$badge_label = !empty($term_name)
    ? sprintf(__('Explore %s', 'hoiantravel'), $term_name)
    : __('Curated Itineraries', 'hoiantravel');

global $wp_query;
$post_count = isset($wp_query->post_count) ? (int) $wp_query->post_count : 0;
$max_pages = isset($wp_query->max_num_pages) ? (int) $wp_query->max_num_pages : 1;
?>

<?php if (have_posts()): ?>
    <section class="ha-section grid-tour-section" aria-label="<?= esc_attr($display_heading); ?>">
        <div class="container">
            <!-- Editorial Section Header -->
            <div class="grid-tour-section__header">
                <div class="grid-tour-section__header-content">
                    <div class="grid-tour-section__badge-wrap">
                        <span class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($badge_label); ?></span>
                        </span>
                    </div>

                    <h2 class="grid-tour-section__title ha-heading">
                        <?= esc_html($display_heading); ?>
                    </h2>
                </div>
            </div>


            <!-- 3-Column Responsive Tour Grid -->
            <div class="grid-tour-section__grid grid-tour-section__grid--count-<?= esc_attr($post_count); ?>"
                id="ha-grid-tours-results">
                <?php
                /* Start the Loop */
                while (have_posts()):
                    the_post();
                    ha_item_tour();
                endwhile;
                ?>
            </div>

            <!-- Pagination Wrapper (Preserves AJAX data attributes) -->
            <div class="grid-tour-section__pagination<?= $max_pages > 1 ? ' mt-5' : ''; ?>" id="ha-grid-tours-pagination"
                data-ajax="true" data-action="ha_ajax_load_grid_tours" data-container="#ha-grid-tours-results"
                data-nonce="<?= esc_attr(wp_create_nonce('ha_load_grid_tours')); ?>"
                data-params="<?= esc_attr(wp_json_encode(['category' => $term_slug])); ?>">
                <?php
                $current_paged = get_query_var('paged') ? get_query_var('paged') : 1;
                ha_pagination($current_paged, $max_pages);
                ?>
            </div>

        </div>
    </section>
<?php endif; ?>