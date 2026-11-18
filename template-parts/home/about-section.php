<?php
$heading     = get_field('heading_ab_home');
$sub_heading = get_field('sub_heading_ab_home');
$desc        = get_field('desc_ab_home');
$badge       = get_field('badge_ab_home');
$cards       = get_field('list_cards');
$image       = get_field('image_ab_home');
$sub_hd_img  = get_field('sub_hd_image_ab_home');
$hd_img      = get_field('hd_image_ab_home');

$img_url = '';
$img_alt = !empty($heading) ? $heading : 'Hoi An Travel Local Story';
if (is_array($image)) {
    $img_url = !empty($image['sizes']['large']) ? $image['sizes']['large'] : ($image['url'] ?? '');
    if (!empty($image['alt'])) {
        $img_alt = $image['alt'];
    }
} elseif (is_string($image)) {
    $img_url = $image;
}
?>

<section class="ha-section about-section" id="about" aria-label="<?php esc_attr_e('About Hoi An Travel', 'hoiantravel'); ?>">
    <!-- Background Heritage Graphic Watermark: Shophouse Rooftops & Wooden Architectural Arches -->
    <div aria-hidden="true" class="about-section__watermark">
        <?php echo ha_svg_icon('about-watermark'); ?>
    </div>
    <!-- Content Container -->
    <div class="container">
        <div class="about-section__grid">
            <!-- LEFT COLUMN (Human Touch & Official Credentials) -->
            <div class="about-section__media">
                <div class="about-section__media-wrap">
                    <div class="about-section__frame-decor" aria-hidden="true"></div>
                    <?php if (!empty($img_url)): ?>
                        <!-- Main Image -->
                        <div class="about-section__image-box">
                            <img alt="<?= esc_attr($img_alt) ?>" class="about-section__image"
                                src="<?= esc_url($img_url) ?>" loading="lazy" />
                        </div>
                    <?php endif; ?>

                    <!-- Floating Inset Badge / Stamp Card overlapping bottom-right corner -->
                    <?php if (!empty($hd_img) || !empty($sub_hd_img)): ?>
                        <div class="about-section__badge-card">
                            <div class="about-section__badge-icon" aria-hidden="true">
                                <?php echo ha_svg_icon('shield-check'); ?>
                            </div>
                            <div class="about-section__badge-content">
                                <?php if (!empty($hd_img)): ?>
                                    <h4 class="about-section__badge-title h6">
                                        <?= esc_html($hd_img) ?>
                                    </h4>
                                <?php endif; ?>
                                <?php if (!empty($sub_hd_img)): ?>
                                    <p class="about-section__badge-desc">
                                        <?= esc_html($sub_hd_img) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN (Brand Narrative & Core Services) -->
            <div class="about-section__content">
                <!-- Editorial Eyebrow Badge -->
                <?php if (!empty($badge)): ?>
                    <div class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('feature-lantern'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($badge) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="about-section__title ha-heading">
                        <?= esc_html($heading) ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($sub_heading)): ?>
                    <p class="about-section__subtitle">
                        <?= esc_html($sub_heading) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <!-- Story Excerpt -->
                    <div class="about-section__excerpt">
                        <?= wp_kses_post(nl2br($desc)) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cards) && is_array($cards)): ?>
                    <!-- 3 Core Specialization Cards -->
                    <div class="about-section__features">
                        <?php foreach ($cards as $key => $card): ?>
                            <?php
                            $f_icon    = $card['icon'] ?? '';
                            $f_heading = $card['heading'] ?? '';
                            ?>
                            <div class="about-feature-card">
                                <?php if (!empty($f_icon)): ?>
                                    <div class="about-feature-card__icon">
                                        <img src="<?= esc_url($f_icon) ?>"
                                            alt="<?= esc_attr('hoi an travel icon about for ' . $f_heading) ?>"
                                            loading="lazy" />
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($f_heading)): ?>
                                    <span class="about-feature-card__title">
                                        <?= esc_html($f_heading) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Founder's Personal Commitment Strip -->
                <div class="about-section__commitment">
                    <blockquote class="about-section__quote">
                        “Together with my team, we will make your time in Vietnam wonderful and unforgettable.”
                    </blockquote>
                </div>
            </div>
        </div>
    </div>
</section>
