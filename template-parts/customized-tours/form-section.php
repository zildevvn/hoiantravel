<?php
/**
 * Customized Tours Template - Form Section
 *
 * Modern Concierge Trust & Booking Layout
 * Fully synchronized with Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$heading = get_field('hd_form_customized');
$hd_intro = get_field('hd_intro_form_customized');
$desc = get_field('desc_contact');
$form_contact = get_field('shortcode_form_customized');
$image = get_field('image_form_customized');
$trip_image = get_field('trip_image_customized');
$address = get_field('address', 'option');
$phone = get_field('phone', 'option');
$email = get_field('email', 'option');
$open_time = get_field('open_time', 'option');

// Phone links & WhatsApp conversion logic
$tel_digits = !empty($phone) ? preg_replace('/[^\d+]/', '', $phone) : '';
$clean_digits = !empty($phone) ? preg_replace('/\D/', '', $phone) : '';
if (!empty($clean_digits) && str_starts_with($clean_digits, '0')) {
    $clean_digits = '84' . substr($clean_digits, 1);
}
$whatsapp_url = !empty($clean_digits) ? 'https://wa.me/' . $clean_digits : '';
?>

<section class="ha-section form-section" id="form-section"
    aria-label="<?php esc_attr_e('Customized Tour Booking and Inquiry Form', 'hoiantravel'); ?>">
    <div class="container">
        <!-- Section Header -->
        <div class="form-section__header text-center mx-auto">
            <?php if (!empty($hd_intro)): ?>
                <div class="form-section__badge-wrap">
                    <span class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($hd_intro); ?></span>
                    </span>
                </div>
            <?php endif; ?>

            <?php if (!empty($heading)): ?>
                <h2 class="form-section__title ha-heading">
                    <?= esc_html($heading); ?>
                </h2>
            <?php endif; ?>

            <div class="form-section__divider" aria-hidden="true"></div>

            <?php if (!empty($desc)): ?>
                <p class="form-section__subtitle">
                    <?= esc_html($desc); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Main 2-Column Composition -->
        <div class="form-section__wrap">
            <!-- Left Column: Concierge Information & Trust Signals (42%) -->
            <div class="form-section__left">
                <!-- Featured Visual Card -->
                <?php if (!empty($image)): ?>
                    <div class="form-section__visual-card">
                        <div class="form-section__visual-frame">
                            <img src="<?= esc_url($image); ?>"
                                alt="<?= esc_attr(!empty($heading) ? $heading : __('Customized Hoi An Tours', 'hoiantravel')); ?>"
                                loading="lazy" />
                            <div class="form-section__visual-overlay" aria-hidden="true"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Concierge Contact Channels Card -->
                <div class="form-section__contact-card">
                    <h3 class="form-section__contact-title">
                        <?php esc_html_e("Let's Connect", 'hoiantravel'); ?>
                    </h3>

                    <div class="form-section__contact-list">
                        <!-- WhatsApp Link -->
                        <?php if (!empty($whatsapp_url) && !empty($phone)): ?>
                            <a href="<?= esc_url($whatsapp_url); ?>" class="form-contact-item" target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php esc_attr_e('Chat with us on WhatsApp', 'hoiantravel'); ?>">
                                <div class="form-contact-item__icon form-contact-item__icon--whatsapp" aria-hidden="true">
                                    <?= ha_svg_icon('whatsapp-chat'); ?>
                                </div>
                                <div class="form-contact-item__content">
                                    <span
                                        class="form-contact-item__label"><?php esc_html_e('WhatsApp', 'hoiantravel'); ?></span>
                                    <span class="form-contact-item__value"><?= esc_html($phone); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <!-- Phone Hotline Link -->
                        <?php if (!empty($phone) && !empty($tel_digits)): ?>
                            <a href="tel:<?= esc_attr($tel_digits); ?>" class="form-contact-item"
                                aria-label="<?php esc_attr_e('Call our phone hotline', 'hoiantravel'); ?>">
                                <div class="form-contact-item__icon form-contact-item__icon--phone" aria-hidden="true">
                                    <?= ha_svg_icon('phone'); ?>
                                </div>
                                <div class="form-contact-item__content">
                                    <span
                                        class="form-contact-item__label"><?php esc_html_e('Hotline Phone', 'hoiantravel'); ?></span>
                                    <span class="form-contact-item__value"><?= esc_html($phone); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <!-- Email Link -->
                        <?php if (!empty($email)): ?>
                            <a href="mailto:<?= esc_attr($email); ?>" class="form-contact-item"
                                aria-label="<?php esc_attr_e('Send us an email', 'hoiantravel'); ?>">
                                <div class="form-contact-item__icon form-contact-item__icon--email" aria-hidden="true">
                                    <?= ha_svg_icon('mail-check'); ?>
                                </div>
                                <div class="form-contact-item__content">
                                    <span
                                        class="form-contact-item__label"><?php esc_html_e('Email Us', 'hoiantravel'); ?></span>
                                    <span class="form-contact-item__value"><?= esc_html($email); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <!-- Office Address -->
                        <?php if (!empty($address)): ?>
                            <div class="form-contact-item form-contact-item--static">
                                <div class="form-contact-item__icon form-contact-item__icon--address" aria-hidden="true">
                                    <?= ha_svg_icon('map-pin-check'); ?>
                                </div>
                                <div class="form-contact-item__content">
                                    <span
                                        class="form-contact-item__label"><?php esc_html_e('Office Address', 'hoiantravel'); ?></span>
                                    <span class="form-contact-item__value"><?= esc_html($address); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Working Hours (if configured) -->
                        <?php if (!empty($open_time)): ?>
                            <div class="form-contact-item form-contact-item--static">
                                <div class="form-contact-item__icon form-contact-item__icon--time" aria-hidden="true">
                                    <?= ha_svg_icon('calendar'); ?>
                                </div>
                                <div class="form-contact-item__content">
                                    <span
                                        class="form-contact-item__label"><?php esc_html_e('Working Hours', 'hoiantravel'); ?></span>
                                    <span class="form-contact-item__value"><?= esc_html($open_time); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TripAdvisor Trust Card -->
                <?php if (!empty($trip_image)): ?>
                    <div class="form-section__trust-card">
                        <a href="https://www.tripadvisor.com/Attraction_Review-g293926-d5569598-Reviews-VM_Travel-Hue_Thua_Thien_Hue_Province.html"
                            target="_blank" rel="noopener noreferrer" class="form-section__trust-link"
                            aria-label="<?php esc_attr_e('Read our traveler reviews on TripAdvisor', 'hoiantravel'); ?>">
                            <img src="<?= esc_url($trip_image); ?>"
                                alt="<?php esc_attr_e('TripAdvisor Travelers Choice', 'hoiantravel'); ?>" loading="lazy" />
                            <span class="form-section__trust-caption">
                                <span><?php esc_html_e('See TripAdvisor Reviews', 'hoiantravel'); ?></span>
                                <?= ha_svg_icon('arrow-up-right'); ?>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Customized Form Card (58%) -->
            <div class="form-section__right">
                <div class="customized-form-card">
                    <!-- Interactive Form Container -->
                    <div id="form-customize-tour" class="form-wrapper">
                        <?php if (!empty($form_contact)): ?>
                            <?= do_shortcode($form_contact); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>