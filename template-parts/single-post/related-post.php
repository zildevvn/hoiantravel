<?php
/**
 * Single Post Related Posts Section
 *
 * @package hoiantravel
 */

$current_post_id = get_the_ID();
$categories      = get_the_category($current_post_id);
$cat_ids         = (!empty($categories) && !is_wp_error($categories)) ? wp_list_pluck($categories, 'term_id') : [];

$args = [
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post_status'         => 'publish',
    'post__not_in'        => [$current_post_id],
    'category__in'        => $cat_ids,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
];

$the_query = new WP_Query($args);

// Fallback to latest posts if not enough posts in the same category
if ($the_query->post_count < 3) {
    $needed = 3;
    $fallback_args = [
        'post_type'           => 'post',
        'posts_per_page'      => $needed,
        'post_status'         => 'publish',
        'post__not_in'        => [$current_post_id],
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    ];
    $the_query = new WP_Query($fallback_args);
}
?>

<?php if ($the_query->have_posts()): ?>
    <section class="vm-section related-post-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="ha-badge">
                    <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('tag'); ?></span>
                    <span class="ha-badge__text"><?= esc_html__('Further Reading', 'hoiantravel'); ?></span>
                </span>
                <h2 class="vm-heading">
                    <?= esc_html__('Related', 'hoiantravel'); ?> <span><?= esc_html__('Articles', 'hoiantravel'); ?></span>
                </h2>
                <p class="vm-sub-heading">
                    <?= esc_html__('Handcrafted travel tips, cultural stories, and local guides for your unforgettable journey in Hoi An.', 'hoiantravel'); ?>
                </p>
            </div>

            <div class="related-post-section__grid">
                <?php
                while ($the_query->have_posts()):
                    $the_query->the_post();
                    ha_item_post(get_the_ID());
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>
