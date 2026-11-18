<?php
$badge       = get_field('badge_banner_home');
$heading     = get_field('heading_banner_home');
$sub_heading = get_field('sub_heading_banner_home');
$button      = get_field('button_heading_banner_home');
$banner_img  = get_field('image_heading_banner_home');
$services    = get_field('list_services_banner_home');

// Resolve banner background image dynamically (never hardcode localhost)
$banner_img_url = '';
if (is_array($banner_img) && !empty($banner_img['url'])) {
    $banner_img_url = $banner_img['url'];
} elseif (is_string($banner_img) && !empty($banner_img)) {
    // Replace any legacy localhost domain saved in DB with current site home_url
    $banner_img_url = preg_replace('#^https?://localhost(?::\d+)?#i', untrailingslashit(home_url()), $banner_img);
}

if (empty($banner_img_url)) {
    $upload_dir  = wp_upload_dir();
    $fallback_rel = '/2026/11/image-banner-ha.webp';
    if (!empty($upload_dir['basedir']) && file_exists($upload_dir['basedir'] . $fallback_rel)) {
        $banner_img_url = $upload_dir['baseurl'] . $fallback_rel;
    }
}

// Resolve CTA button from ACF link field with safe fallback
$btn_url    = '#transfers';
$btn_title  = __('BOOK NOW', 'hoiantravel');
$btn_target = '_self';

if (is_array($button) && !empty($button['url'])) {
    $btn_url    = $button['url'];
    $btn_title  = !empty($button['title']) ? $button['title'] : $btn_title;
    $btn_target = !empty($button['target']) ? $button['target'] : '_self';
}
?>
<section class="ha-section banner-section" id="section-car-rental-banner" aria-label="<?php esc_attr_e('Private Chauffeur & Car Charter Service', 'hoiantravel'); ?>">
    <div class="container">
        <div class="banner-section__card">
            <?php if (!empty($banner_img_url)): ?>
                <img alt="<?= esc_attr(!empty($heading) ? $heading : __('Hoi An Car Rental Chauffeur Service', 'hoiantravel')) ?>"
                    class="banner-section__bg-img"
                    src="<?= esc_url($banner_img_url) ?>"
                    loading="lazy" />
            <?php endif; ?>

            <div class="banner-section__overlay" aria-hidden="true"></div>
            <div class="banner-section__glow" aria-hidden="true"></div>
            <div class="banner-section__frame" aria-hidden="true"></div>

            <div class="banner-section__content">
                <?php if (!empty($badge)): ?>
                    <div class="ha-badge ha-badge--on-dark">
                        <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('car-simple'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($badge) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="banner-section__title ha-heading">
                        <?= esc_html($heading) ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sub_heading)): ?>
                    <p class="banner-section__subtitle">
                        <?= esc_html($sub_heading) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($services) && is_array($services)): ?>
                    <div class="banner-section__features">
                        <?php foreach ($services as $service): ?>
                            <?php if (!empty($service['service_item'])): ?>
                                <span class="banner-section__feature-item">
                                    <span class="banner-section__feature-icon" aria-hidden="true"><?php echo ha_svg_icon('check'); ?></span>
                                    <span><?= esc_html($service['service_item']) ?></span>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="banner-section__actions">
                    <a class="ha-button" href="<?= esc_url($btn_url) ?>" target="<?= esc_attr($btn_target) ?>"<?= $btn_target === '_blank' ? ' rel="noopener noreferrer"' : '' ?>>
                        <?= esc_html($btn_title) ?>
                        <?php echo ha_svg_icon('arrow_next'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
