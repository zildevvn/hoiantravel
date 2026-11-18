<?php
/**
 * Single Car Related Vehicles Section
 *
 * @package hoiantravel
 */

$current_id = get_the_ID();
$args = array(
    'post_type' => 'cars',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => array($current_id),
);
$query = new WP_Query($args);

if (!$query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>

<section class="ha-section single-car-related-section bg-light" style="padding: 60px 0; background: #faf8f5;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="ha-badge mb-2">
                    <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('car'); ?></span>
                    <span class="ha-badge__text"><?php esc_html_e('Explore More Routes', 'hoiantravel'); ?></span>
                </span>
                <h2 class="ha-heading h3 mb-0">
                    <?php esc_html_e('Other Popular Private Car Transfers', 'hoiantravel'); ?>
                </h2>
            </div>
            <a href="<?= esc_url(home_url('/hoi-an-car-rental/')); ?>" class="ha-button d-none d-md-inline-flex">
                 <?php esc_html_e('View All Cars', 'hoiantravel'); ?>
                <?= ha_svg_icon('arrow-right'); ?>
            </a>
        </div>

        <div class="cars-grid">
            <?php while ($query->have_posts()):
                $query->the_post();
                ha_item_car();
            endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>