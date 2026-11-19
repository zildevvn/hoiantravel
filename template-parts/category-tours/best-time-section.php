<?php
/**
 * Category Tours - Best Time Section
 *
 * Synchronized visually & structurally with Blog Template's `experiences-section`
 * (`template-parts/blog/experiences-section.php`) using `.ha-media` split layout,
 * while preserving Category Tours' own ACF fields:
 * `badge_best_time_cate_tour_tpl`, `heading_best_time_cate_tour_tpl`,
 * `desc_best_time_cate_tour_tpl`, and `img_best_time_cate_tour_tpl`.
 *
 * @package hoiantravel
 */

$badge = function_exists('get_field') ? get_field('badge_best_time_cate_tour_tpl') : '';
$heading = function_exists('get_field') ? get_field('heading_best_time_cate_tour_tpl') : '';
$desc = function_exists('get_field') ? get_field('desc_best_time_cate_tour_tpl') : '';
$image = function_exists('get_field') ? get_field('img_best_time_cate_tour_tpl') : null;

$image_url = '';
$image_alt = '';
if (is_array($image)) {
    $image_url = !empty($image['sizes']['large']) ? $image['sizes']['large'] : (!empty($image['url']) ? $image['url'] : '');
    $image_alt = !empty($image['alt']) ? $image['alt'] : '';
} elseif (is_numeric($image)) {
    $image_url = wp_get_attachment_image_url((int) $image, 'large');
    $image_alt = get_post_meta((int) $image, '_wp_attachment_image_alt', true);
} elseif (is_string($image)) {
    $image_url = $image;
}

if (empty($image_alt)) {
    $image_alt = !empty($heading) ? $heading : __('Best time to visit Hoi An', 'hoiantravel');
}

if (empty($badge) && empty($heading) && empty($desc) && empty($image_url)) {
    return;
}
?>

<section class="ha-section experiences-section category-tours-best-time" id="category-tours-best-time">
    <div class="container">
        <div class="ha-media">
            <div class="ha-media__content">
                <?php if (!empty($badge)): ?>
                    <span class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($badge); ?></span>
                    </span>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="ha-heading h3 mb-0">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <div class="ha-sub-heading mt-4">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($image_url)): ?>
                <div class="ha-media__image">
                    <img src="<?= esc_url($image_url); ?>" alt="<?= esc_attr($image_alt); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>