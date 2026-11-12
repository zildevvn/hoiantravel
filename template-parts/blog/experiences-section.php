<?php
$badge = get_field('badge_experiences_tpl_blog');
$heading = get_field('heading_experiences_tpl_blog');
$desc = get_field('desc_experiences_tpl_blog');
$image = get_field('img_experiences_tpl_blog');

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
    $image_alt = $heading ?: __('experiences Hoi An Destinations', 'hoiantravel');
}
?>
<section class="ha-section experiences-section">
    <div class="container">
        <div class="ha-media">
            <div class="ha-media__content">
                <?php if ($badge): ?>
                    <span class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= esc_html($badge); ?>
                    </span>
                <?php endif; ?>

                <?php if ($heading): ?>
                    <h2 class="ha-heading h3 mb-0">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($desc): ?>
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