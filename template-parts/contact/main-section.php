<?php
/**
 * Contact Page Main Section
 *
 * Architecture:
 * 1. Intro Heading with luxury brand badge
 * 2. 2-Column Responsive Layout:
 *    - Left: Direct Contact Channels (WhatsApp 24/7 Hero, Hotline phone, Email, Central Vietnam Stations, Hours)
 *    - Right: Interactive Inquiry & Booking Form (Gravity Forms / Ninja Forms via do_shortcode)
 * 3. Trust signals bar (Fixed price, Free cancellation, English speaking drivers)
 */

// ACF Fields from Contact Template
$heading = get_field('heading_contact');
$desc = get_field('desc_contact');
$form_contact = get_field('form_contact');

// Fallback heading & description
if (empty($heading)) {
    $heading = __('Connect with Our Concierge Team', 'hoiantravel');
}
if (empty($desc)) {
    $desc = __('Whether you need a private car transfer from Da Nang Airport to Hoi An, a customized day trip across Central Vietnam, or immediate travel assistance, our local team is available 24/7.', 'hoiantravel');
}

// ACF Fields from Theme Options
$phone = get_field('phone', 'option') ?: '+84 901 971 515';
$email = get_field('email', 'option') ?: 'vmtravelhoian@gmail.com';
$address = get_field('address', 'option') ?: '08 Tran Cao Van Street, Hoi An & Da Nang, Vietnam';
$open_time = get_field('open_time', 'option') ?: '07:00 AM – 10:00 PM';
$socials = get_field('socials', 'option');
$locations = get_field('footer_locations', 'option');

// Phone links & WhatsApp conversion logic
$tel_digits = preg_replace('/[^\d+]/', '', $phone);
$clean_digits = preg_replace('/\D/', '', $phone);
if (str_starts_with($clean_digits, '0')) {
    $clean_digits = '84' . substr($clean_digits, 1);
}
$whatsapp_url = 'https://wa.me/' . ($clean_digits ?: '84901971515');

// Normalize form contact shortcode
if (empty($form_contact)) {
    $form_contact = '[gravityform id="1" title="false" description="false" ajax="true"]';
} elseif (is_numeric($form_contact)) {
    $form_contact = sprintf('[gravityform id="%d" title="false" description="false" ajax="true"]', (int) $form_contact);
}
?>

<section class="ha-section contact-main-section" id="contact-main-section">
    <div class="container">
        <!-- Section Header -->
        <div class="contact-main__header text-center mx-auto">
            <span class="ha-badge mb-3">
                <?= ha_svg_icon('badge'); ?>
                <span><?php esc_html_e('Direct Support & Booking', 'hoiantravel'); ?></span>
            </span>
            <h2 class="contact-main__heading">
                <?= esc_html($heading); ?>
            </h2>
            <?php if (!empty($desc)): ?>
                <div class="contact-main__desc">
                    <?= wp_kses_post(wpautop($desc)); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Main 2-Column Composition -->
        <div class="contact-main__grid">
            <!-- Left Column: Direct Channels & Hubs -->
            <div class="contact-main__info">
                <!-- Highlight Hero Channel: WhatsApp 24/7 -->
                <div class="contact-channel-hero">
                    <div class="contact-channel-hero__badge">
                        <span class="pulse-dot" aria-hidden="true"></span>
                        <span><?php esc_html_e('Online 24/7 • Instant Reply', 'hoiantravel'); ?></span>
                    </div>
                    <div class="contact-channel-hero__body">
                        <div class="contact-channel-hero__icon" aria-hidden="true">
                            <?= ha_svg_icon('whatsapp'); ?>
                        </div>
                        <div class="contact-channel-hero__content">
                            <h3 class="contact-channel-hero__title">
                                <?php esc_html_e('WhatsApp Concierge', 'hoiantravel'); ?>
                            </h3>
                            <p class="contact-channel-hero__desc">
                                <?php esc_html_e('Fastest way to plan your Hoi An journey, check tour availability, and receive instant support.', 'hoiantravel'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="contact-channel-hero__action">
                        <a href="<?= esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer"
                            class="ha-button"
                            aria-label="<?php esc_attr_e('Chat with us on WhatsApp', 'hoiantravel'); ?>">
                            <span class="contact-btn-icon" aria-hidden="true"><?= ha_svg_icon('whatsapp'); ?></span>
                            Chat on WhatsApp
                            <?= ha_svg_icon('arrow-right'); ?>
                        </a>
                    </div>
                </div>

                <!-- Secondary Quick Contact Cards -->
                <div class="contact-channels-subgrid">
                    <!-- Phone Call Card -->
                    <a href="tel:<?= esc_attr($tel_digits); ?>" class="contact-subcard"
                        aria-label="<?php esc_attr_e('Call us directly', 'hoiantravel'); ?>">
                        <div class="contact-subcard__icon contact-subcard__icon--phone" aria-hidden="true">
                            <?= ha_svg_icon('phone'); ?>
                        </div>
                        <div class="contact-subcard__content">
                            <span
                                class="contact-subcard__label"><?php esc_html_e('Hotline & Phone Call', 'hoiantravel'); ?></span>
                            <span class="contact-subcard__value"><?= esc_html($phone); ?></span>
                            <span
                                class="contact-subcard__hint"><?php esc_html_e('Direct voice call 24/7', 'hoiantravel'); ?></span>
                        </div>
                    </a>

                    <!-- Email Card -->
                    <a href="mailto:<?= esc_attr($email); ?>" class="contact-subcard"
                        aria-label="<?php esc_attr_e('Send an email inquiry', 'hoiantravel'); ?>">
                        <div class="contact-subcard__icon contact-subcard__icon--email" aria-hidden="true">
                            <?= ha_svg_icon('email'); ?>
                        </div>
                        <div class="contact-subcard__content">
                            <span
                                class="contact-subcard__label"><?php esc_html_e('Email Inquiries', 'hoiantravel'); ?></span>
                            <span class="contact-subcard__value"><?= esc_html($email); ?></span>
                            <span
                                class="contact-subcard__hint"><?php esc_html_e('Quotes & tailored itineraries', 'hoiantravel'); ?></span>
                        </div>
                    </a>
                </div>

                <!-- Physical Station & Hours Card -->
                <div class="contact-office-card">
                    <div class="contact-office-card__item">
                        <div class="contact-office-card__icon" aria-hidden="true">
                            <?= ha_svg_icon('location'); ?>
                        </div>
                        <div class="contact-office-card__info">
                            <h4 class="contact-office-card__title">
                                <?php esc_html_e('Operating Hub & Address', 'hoiantravel'); ?>
                            </h4>
                            <p class="contact-office-card__text"><?= esc_html($address); ?></p>
                        </div>
                    </div>

                    <?php if (!empty($locations) && is_array($locations)): ?>
                        <div class="contact-stations-list">
                            <?php foreach ($locations as $loc): ?>
                                <?php if (!empty($loc['name'])): ?>
                                    <div class="contact-station-chip">
                                        <span class="station-chip__dot" aria-hidden="true"></span>
                                        <strong><?= esc_html($loc['name']); ?>:</strong>
                                        <span><?= esc_html($loc['address'] ?? ''); ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="contact-office-card__item contact-office-card__item--hours">
                        <div class="contact-office-card__icon" aria-hidden="true">
                            <?= ha_svg_icon('clock'); ?>
                        </div>
                        <div class="contact-office-card__info">
                            <h4 class="contact-office-card__title"><?php esc_html_e('Service Hours', 'hoiantravel'); ?>
                            </h4>
                            <p class="contact-office-card__text"><?= esc_html($open_time); ?></p>
                        </div>
                    </div>

                    <?php if (!empty($socials) && is_array($socials)): ?>
                        <div class="contact-social-bar">
                            <span
                                class="contact-social-bar__title"><?php esc_html_e('Connect on social:', 'hoiantravel'); ?></span>
                            <div class="contact-social-bar__links">
                                <?php foreach ($socials as $idx => $soc): ?>
                                    <?php if (!empty($soc['link'])): ?>
                                        <a href="<?= esc_url($soc['link']); ?>" class="contact-social-btn" target="_blank"
                                            rel="noopener noreferrer"
                                            aria-label="<?php echo esc_attr(sprintf(__('Social link %d', 'hoiantravel'), $idx + 1)); ?>">
                                            <?php if (!empty($soc['icon'])): ?>
                                                <img src="<?= esc_url($soc['icon']); ?>" alt="" width="18" height="18" loading="lazy" />
                                            <?php else: ?>
                                                <?= ha_svg_icon('arrow-right'); ?>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Interactive Contact & Booking Form -->
            <div class="contact-main__form">
                <div class="contact-form-card">
                    <div class="contact-form-card__header">
                        <span class="contact-form-card__badge">
                            <?= ha_svg_icon('badge'); ?>
                            <span><?php esc_html_e('Hoi An Tours & Inquiries', 'hoiantravel'); ?></span>
                        </span>
                        <h3 class="contact-form-card__title">
                            <?php esc_html_e('Send Us a Message', 'hoiantravel'); ?>
                        </h3>
                    </div>

                    <div class="contact-form-wrapper">
                        <?= do_shortcode($form_contact); ?>
                    </div>

                    <!-- Trust Signals Bar Below Form -->
                    <div class="contact-form-trust">
                        <div class="contact-form-trust__item">
                            <span class="trust-icon" aria-hidden="true"><?= ha_svg_icon('check'); ?></span>
                            <span><?php esc_html_e('Fixed price, all taxes & tolls included', 'hoiantravel'); ?></span>
                        </div>
                        <div class="contact-form-trust__item">
                            <span class="trust-icon" aria-hidden="true"><?= ha_svg_icon('shield-check'); ?></span>
                            <span><?php esc_html_e('Free cancellation up to 24h prior', 'hoiantravel'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>