<?php
/**
 * Single Tour Related Tours Section
 *
 * Shows curated similar tours from the same category
 *
 * @package hoiantravel
 */

$current_post_id = get_the_ID();

$current_tour_cats = wp_get_post_terms(
    $current_post_id,
    'tour_cats',
    [
        'fields' => 'names',
    ]
);

$args = [
    'post_type' => 'tours',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => [$current_post_id],
    'orderby' => 'rand',
];

if (!is_wp_error($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cat_ids = wp_get_post_terms(
        $current_post_id,
        'tour_cats',
        [
            'fields' => 'ids',
        ]
    );

    $args['tax_query'] = [
        [
            'taxonomy' => 'tour_cats',
            'field' => 'term_id',
            'terms' => $current_tour_cat_ids,
        ],
    ];
}

$the_query = new WP_Query($args);

$heading = __('Other Recommended Tours', 'ha');

if (is_array($current_tour_cats) && !empty($current_tour_cats)) {
    $current_tour_cats = array_filter(array_map('trim', $current_tour_cats));

    if (!empty($current_tour_cats)) {
        $heading = sprintf(__('Other %s', 'ha'), '<span>' . esc_html(implode(' & ', $current_tour_cats)) . '</span>');
    }
}
?>

<?php if ($the_query->have_posts()): ?>
    <section class="ha-section related-tour-section" id="related-tours">
        <div class="container">
            <div class="related-tour-section__header text-center">
                <span class="ha-badge">
                    <?= ha_svg_icon('tour-package'); ?>
                    <?php esc_html_e('Explore More', 'ha'); ?>
                </span>
                <h2 class="ha-heading related-tour-section__title">
                    <?php echo wp_kses_post($heading); ?>
                </h2>
                <p class="ha-sub-heading related-tour-section__subtitle">
                    <?php esc_html_e('Discover more handcrafted journeys, cultural heritage and authentic experiences in Hoi An and Da Nang.', 'ha'); ?>
                </p>
            </div>

            <div class="related-tour-section__grid">
                <?php
                while ($the_query->have_posts()):
                    $the_query->the_post();
                    ?>
                    <div class="related-tour-section__col">
                        <?php ha_item_tour(); ?>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>
