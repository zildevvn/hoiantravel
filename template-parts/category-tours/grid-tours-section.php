<?php
/**
 * Category Tours - Grid Tours Section
 *
 * Synchronized with Hoi An Travel's `.grid-tour-section` design system
 * (`assets/scss/pages/_term-tour-locations.scss`), preserving Category Tours'
 * own ACF fields (`hd_list_tour_cate_tpl`, `sub_hd_list_tour_cate_tpl`,
 * `select_term_tour_cate_tpl`, `select_category_tour_tpl`, `select_location_tour_tpl`).
 *
 * @package hoiantravel
 */

$heading     = function_exists('get_field') ? get_field('hd_list_tour_cate_tpl') : '';
$sub_heading = function_exists('get_field') ? get_field('sub_hd_list_tour_cate_tpl') : '';
$category    = function_exists('get_field') ? get_field('select_category_tour_tpl') : null;
$location    = function_exists('get_field') ? get_field('select_location_tour_tpl') : null;
$terms       = function_exists('get_field') ? get_field('select_term_tour_cate_tpl') : '';

$term = ($terms === 'category')
    ? 'tour_cats'
    : (($terms === 'location') ? 'tour_locations' : '');

$selected_term = null;
if ($term) {
    $selected_term = ($terms === 'category') ? $category : $location;
}

$paged = get_query_var('paged')
    ? (int) get_query_var('paged')
    : (get_query_var('page') ? (int) get_query_var('page') : 1);
$paged = max(1, $paged);

$args = [
    'post_type'      => 'tours',
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'paged'          => $paged,
];

if ($term && $selected_term instanceof WP_Term) {
    $args['tax_query'] = [
        [
            'taxonomy' => $term,
            'field'    => 'slug',
            'terms'    => $selected_term->slug,
        ],
    ];
}

$query       = new WP_Query($args);
$post_count  = isset($query->post_count) ? (int) $query->post_count : 0;
$max_pages   = isset($query->max_num_pages) ? (int) $query->max_num_pages : 1;
$badge_label = ($selected_term instanceof WP_Term && !empty($selected_term->name))
    ? sprintf(__('Explore %s', 'hoiantravel'), $selected_term->name)
    : __('Curated Itineraries', 'hoiantravel');
?>

<?php if ($selected_term instanceof WP_Term && $query->have_posts()): ?>
    <section class="ha-section grid-tour-section" aria-label="<?= esc_attr(!empty($heading) ? $heading : $badge_label); ?>">
        <div class="container">
            <div class="grid-tour-section__header">
                <div class="grid-tour-section__header-content">
                    <div class="grid-tour-section__badge-wrap">
                        <span class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($badge_label); ?></span>
                        </span>
                    </div>

                    <?php if (!empty($heading)): ?>
                        <h2 class="grid-tour-section__title ha-heading">
                            <?= esc_html($heading); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($sub_heading)): ?>
                        <p class="grid-tour-section__subtitle ha-sub-heading">
                            <?= esc_html($sub_heading); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid-tour-section__grid grid-tour-section__grid--count-<?= esc_attr($post_count); ?>" id="ha-grid-tours-results">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    if (function_exists('ha_item_tour')) {
                        ha_item_tour();
                    }
                endwhile;
                ?>
            </div>

            <?php if ($max_pages > 1 && function_exists('ha_pagination')): ?>
                <div class="grid-tour-section__pagination mt-5" id="ha-grid-tours-pagination">
                    <?php ha_pagination($paged, $max_pages); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
