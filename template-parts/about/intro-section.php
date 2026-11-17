<?php
/**
 * About Page - Intro Section
 *
 * Modern Asymmetric Storytelling Layout with Layered Visuals
 * Fully synchronized with Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$heading = get_field('hd_intro_tpl_ab');
$sub_hd = get_field('sub_hd_intro_tpl_ab');
$desc = get_field('desc_intro_tpl_ab');
$image_field = get_field('img_intro_tpl_ab');

// Resolve image URL and ALT text cleanly
$image_url = '';
$image_alt = '';

if (!empty($image_field)) {
    if (is_array($image_field)) {
        $image_url = $image_field['url'] ?? '';
        $image_alt = !empty($image_field['alt']) ? $image_field['alt'] : '';
    } else {
        $image_url = (string) $image_field;
    }
}

if (empty($image_alt)) {
    $image_alt = !empty($heading) ? strip_tags($heading) : __('About Hoi An Travel Agency', 'hoiantravel');
}
?>

<section class="ha-section intro-section">
    <!-- Atmospheric Heritage Watermark -->
    <div class="intro-section__watermark" aria-hidden="true">
        <?= ha_svg_icon('about-watermark'); ?>
    </div>

    <div class="container intro-section__container">
        <div class="intro-section__grid">

            <!-- Content & Storytelling Column -->
            <div class="intro-section__content-col">
                <!-- Eyebrow Pill Badge -->
                <?php if (!empty($sub_hd)): ?>
                    <div class="intro-section__badge-wrap">
                        <span class="ha-badge intro-section__badge">
                            <?= ha_svg_icon('badge'); ?>
                            <span><?= esc_html($sub_hd); ?></span>
                        </span>
                    </div>
                <?php endif; ?>

                <!-- Section Main Heading -->
                <?php if (!empty($heading)): ?>
                    <h2 class="ha-heading intro-section__title">
                        <?= wp_kses_post($heading); ?>
                    </h2>
                <?php endif; ?>

                <!-- Rich Story Content (WYSIWYG) -->
                <?php if (!empty($desc)): ?>
                    <div class="intro-section__prose">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>

                <!-- Core Values / Heritage Highlights -->
                <div class="intro-section__highlights">
                    <div class="intro-section__highlight-item">
                        <span class="intro-section__highlight-icon" aria-hidden="true">
                            <?= ha_svg_icon('check'); ?>
                        </span>
                        <span
                            class="intro-section__highlight-text"><?= esc_html__('100% Licensed Local Guides & Private Drivers', 'hoiantravel'); ?></span>
                    </div>
                    <div class="intro-section__highlight-item">
                        <span class="intro-section__highlight-icon" aria-hidden="true">
                            <?= ha_svg_icon('check'); ?>
                        </span>
                        <span
                            class="intro-section__highlight-text"><?= esc_html__('Handcrafted Hoi An, Da Nang & Hue Itineraries', 'hoiantravel'); ?></span>
                    </div>
                    <div class="intro-section__highlight-item">
                        <span class="intro-section__highlight-icon" aria-hidden="true">
                            <?= ha_svg_icon('check'); ?>
                        </span>
                        <span
                            class="intro-section__highlight-text"><?= esc_html__('Fixed Transparent Pricing & 24/7 WhatsApp Support', 'hoiantravel'); ?></span>
                    </div>
                </div>

                <!-- Action Button Group -->
                <div class="intro-section__actions">
                    <a href="<?= esc_url(home_url('/hoi-an-office//')); ?>" class="ha-button">
                        <?= esc_html__('Get in Touch', 'hoiantravel'); ?>
                        <?= ha_svg_icon('arrow-right'); ?>
                    </a>
                </div>
            </div>

            <!-- Media Composition Column -->
            <div class="intro-section__media-col">
                <div class="intro-section__media-wrap">
                    <!-- Offset Indochine Golden Accent Frame -->
                    <div class="intro-section__decor-frame" aria-hidden="true"></div>

                    <!-- Main Image Box -->
                    <?php if (!empty($image_url)): ?>
                        <div class="intro-section__image-box">
                            <img src="<?= esc_url($image_url); ?>" alt="<?= esc_attr($image_alt); ?>"
                                class="intro-section__image" loading="lazy" />
                            <div class="intro-section__image-overlay" aria-hidden="true"></div>
                        </div>
                    <?php else: ?>
                        <div class="intro-section__image-placeholder">
                            <div class="intro-section__placeholder-icon" aria-hidden="true">
                                <?= ha_svg_icon('destination-lanterns'); ?>
                            </div>
                            <span
                                class="intro-section__placeholder-text"><?= esc_html__('Hoi An Travel Experience', 'hoiantravel'); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Floating Heritage Trust Stamp -->
                    <div class="intro-section__badge-card">
                        <div class="intro-section__badge-icon" aria-hidden="true">
                            <?= ha_svg_icon('shield-check'); ?>
                        </div>
                        <div class="intro-section__badge-content">
                            <span
                                class="intro-section__badge-title"><?= esc_html__('Authentic Experience', 'hoiantravel'); ?></span>
                            <span
                                class="intro-section__badge-desc"><?= esc_html__('Local Heritage Specialists', 'hoiantravel'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Operator License Modal (Only active if license exists) -->
<?php if (!empty($license_image)): ?>
    <div class="ha-license-modal" id="licenseModal" role="dialog" aria-modal="true" aria-labelledby="licenseModalTitle">
        <div class="ha-license-modal__overlay js-close-license-modal"></div>
        <div class="ha-license-modal__content">
            <button type="button" class="ha-license-modal__close js-close-license-modal"
                aria-label="<?php esc_attr_e('Close modal', 'hoiantravel'); ?>">
                <?= ha_svg_icon('x-close'); ?>
            </button>
            <div class="ha-license-modal__body">
                <div class="ha-license-modal__grid">
                    <div class="ha-license-modal__image-wrap">
                        <img src="<?= esc_url($license_image); ?>"
                            alt="<?= esc_attr__('Hoi An Travel Tour Operator License', 'hoiantravel'); ?>"
                            class="ha-license-modal__image" loading="lazy" />
                    </div>
                    <div class="ha-license-modal__info">
                        <div class="ha-license-modal__badge">
                            <?= ha_svg_icon('shield-check'); ?>
                            <span><?= esc_html__('Official Certification', 'hoiantravel'); ?></span>
                        </div>
                        <h3 id="licenseModalTitle" class="ha-license-modal__title">
                            <?= esc_html__('International Tour Operator License', 'hoiantravel'); ?>
                        </h3>
                        <?php if (!empty($license_desc)): ?>
                            <div class="ha-license-modal__desc">
                                <?= wp_kses_post($license_desc); ?>
                            </div>
                        <?php else: ?>
                            <p class="ha-license-modal__desc">
                                <?= esc_html__('Officially certified by the Vietnam National Authority of Tourism. Hoi An Travel complies with all international standards of safety, quality, and service excellence.', 'hoiantravel'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>