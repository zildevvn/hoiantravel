<?php
/**
 * Contact Page Team Section — Hoi An Travel Concierge & Local Guides
 *
 * Visual Focal Point: Left-hand media showcase with authentic team image & floating trust stamp.
 * Content Hierarchy: Right-hand brand narrative, badge eyebrow, 3 local value cards, and CTA.
 *
 * @package hoiantravel
 */

// ACF Fields from Contact Template (if configured in the future)
$badge = get_field('badge_team_contact') ?: __('Dedicated Local Concierge', 'hoiantravel');
$heading = get_field('heading_team_contact') ?: __('The Friendly Faces Behind Your Hoi An Journey', 'hoiantravel');
$desc = get_field('desc_team_contact') ?: __('Born and raised in Central Vietnam, our travel coordinators and knowledgeable drivers bring together decades of local hospitality. From tailoring personal itineraries along ancient alleyways to ensuring smooth, safe road trips across the region, we treat every traveler like family.', 'hoiantravel');

// Team image retrieval (ACF or authentic local assets)
$team_image = get_field('image_team_contact');
$team_image_url = '';
$team_image_alt = __('Hoi An Travel Concierge & Local Guide Team', 'hoiantravel');

if (!empty($team_image)) {
    if (is_array($team_image)) {
        $team_image_url = $team_image['sizes']['large'] ?? $team_image['url'];
        $team_image_alt = !empty($team_image['alt']) ? $team_image['alt'] : $team_image_alt;
    } elseif (is_numeric($team_image)) {
        $team_image_url = wp_get_attachment_image_url((int) $team_image, 'large');
        $alt = get_post_meta((int) $team_image, '_wp_attachment_image_alt', true);
        if (!empty($alt)) {
            $team_image_alt = $alt;
        }
    } elseif (is_string($team_image)) {
        $team_image_url = $team_image;
    }
}

// Fallback to theme option if available
if (empty($team_image_url)) {
    $option_image = get_field('team_image', 'option');
    if (!empty($option_image)) {
        $team_image_url = is_array($option_image) ? ($option_image['sizes']['large'] ?? $option_image['url']) : $option_image;
    }
}

// Fallback to authentic uploaded team image in WordPress uploads if available
if (empty($team_image_url)) {
    $upload_dir = wp_upload_dir();
    $candidate_path = $upload_dir['basedir'] . '/2026/11/team-vm-car-1.jpg';
    if (file_exists($candidate_path)) {
        $team_image_url = $upload_dir['baseurl'] . '/2026/11/team-vm-car-1.jpg';
    } else {
        $team_image_url = get_template_directory_uri() . '/screenshot.png';
    }
}

// Experience & Trust badge texts
$badge_title = get_field('badge_title_team_contact') ?: __('100% Local Guides & Drivers', 'hoiantravel');
$badge_desc = get_field('badge_desc_team_contact') ?: __('10+ Years Central Vietnam Hospitality', 'hoiantravel');
?>

<section class="ha-section contact-team-section" id="contact-team-section">
    <!-- Heritage background watermark: Shophouse rooftops in #DFA128 at subtle 3% opacity -->
    <div aria-hidden="true" class="contact-team__watermark">
        <?= ha_svg_icon('about-watermark'); ?>
    </div>

    <div class="container">
        <div class="contact-team__grid">
            <!-- LEFT COLUMN: Visual Focal Point (Team Image + Floating Trust Stamp) -->
            <div class="contact-team__media">
                <div class="contact-team__media-wrap">
                    <div class="contact-team__image-box">
                        <img src="<?= esc_url($team_image_url); ?>" alt="<?= esc_attr($team_image_alt); ?>"
                            class="contact-team__image" loading="lazy" />
                        <div class="contact-team__image-overlay" aria-hidden="true"></div>
                    </div>

                    <!-- Floating Experience & Trust Badge -->
                    <div class="contact-team__badge-card">
                        <div class="contact-team__badge-icon" aria-hidden="true">
                            <?= ha_svg_icon('shield-check'); ?>
                        </div>
                        <div class="contact-team__badge-content">
                            <span class="contact-team__badge-title"><?= esc_html($badge_title); ?></span>
                            <span class="contact-team__badge-desc"><?= esc_html($badge_desc); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Story, Value Cards & Call to Action -->
            <div class="contact-team__content">
                <span class="ha-badge mb-3">
                    <?= ha_svg_icon('users'); ?>
                    <span><?= esc_html($badge); ?></span>
                </span>

                <h2 class="contact-team__heading">
                    <?= esc_html($heading); ?>
                </h2>

                <div class="contact-team__desc">
                    <?= $desc ?>
                </div>

                <!-- 3 Pillars of Local Care -->
                <div class="contact-team__values">
                    <div class="contact-team__value-item">
                        <div class="contact-team__value-icon" aria-hidden="true">
                            <?= ha_svg_icon('location'); ?>
                        </div>
                        <div class="contact-team__value-text">
                            <h4 class="contact-team__value-title">


                                <?php esc_html_e('Born & Raised Central Locals', 'hoiantravel'); ?>
                            </h4>
                            <p class="contact-team__value-desc">
                                <?php esc_html_e('In-depth knowledge of Hoi An, Da Nang, Hue and hidden countryside trails.', 'hoiantravel'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="contact-team__value-item">
                        <div class="contact-team__value-icon" aria -hidden="true">

                            <?= ha_svg_icon('clock'); ?>

                        </div>
                        <div class="contact-team__value-text">
                            <h4 class="contact-team__value-title">
                                <?php esc_html_e('24/7 Dedicated Assistance', 'hoiantravel'); ?>
                            </h4>
                            <p class="contact-team__value-desc">
                                <?php esc_html_e('Always on call via WhatsApp and hotline throughout your time in Vietnam.', 'hoiantravel'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action CTA -->
                <div class="contact-team__actions">
                    <a href="#contact-main-section" class="ha-button" aria-label="">
                        Inquire With Our Team
                        <?= ha_svg_icon('arrow-right'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>