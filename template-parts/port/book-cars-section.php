<?php
/**
 * Port Template - Book Cars Section
 *
 * Modern VIP Port Transfer & Fleet Concierge Layout
 * Fully synchronized with Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$heading = get_field('hd_car_port_tpl');
$sub_hd  = get_field('sub_hd_car_port_tpl');
$form    = get_field('form_car_port_tpl');

// Fallback text if admin fields are empty
$display_heading = !empty($heading) ? $heading : __('Private Car Rental & Port Transfers', 'hoiantravel');
$display_sub_hd  = !empty($sub_hd) ? $sub_hd : __('Comfortable, door-to-door private transfers between Central Vietnam seaports and Hoi An, Da Nang, Hue.', 'hoiantravel');

// Global Options
$phone = get_field('phone', 'option');
$email = get_field('email', 'option');

// Phone link sanitize
$tel_digits = !empty($phone) ? preg_replace('/[^\d+]/', '', $phone) : '';

// Fleet image verification via wp_upload_dir()
$upload_info = wp_upload_dir();
$upload_basedir = $upload_info['basedir'] ?? '';
$upload_baseurl = $upload_info['baseurl'] ?? '';

$fleet_img_url = '';
$fleet_opt_rel = '/2026/11/image-hoi-an-car-rental_-1024x683.webp';
$fleet_full_rel = '/2026/11/image-hoi-an-car-rental_.webp';

if (!empty($upload_basedir) && file_exists($upload_basedir . $fleet_opt_rel)) {
    $fleet_img_url = $upload_baseurl . $fleet_opt_rel;
} elseif (!empty($upload_basedir) && file_exists($upload_basedir . $fleet_full_rel)) {
    $fleet_img_url = $upload_baseurl . $fleet_full_rel;
}
?>

<section id="book-car-section" class="ha-section book-cars-section" aria-label="<?php esc_attr_e('Private Port Transfer Booking', 'hoiantravel'); ?>">
    <div class="container">
        <!-- Section Header -->
        <div class="book-cars-section__header text-center mx-auto">
            <div class="book-cars-section__badge-wrap">
                <span class="ha-badge">
                    <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('car-simple'); ?></span>
                    <span><?php esc_html_e('Port Transfers & Private Fleet', 'hoiantravel'); ?></span>
                </span>
            </div>

            <h2 class="book-cars-section__title ha-heading">
                <?= esc_html($display_heading); ?>
            </h2>

            <div class="book-cars-section__divider" aria-hidden="true"></div>

            <?php if (!empty($display_sub_hd)): ?>
                <p class="book-cars-section__subtitle">
                    <?= esc_html($display_sub_hd); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Main 2-Column Composition (42% / 58%) -->
        <div class="book-cars-section__grid">
            <!-- Left Column: Fleet Visual & Service Information (42%) -->
            <div class="book-cars-section__left">
                <!-- Fleet Visual Card -->
                <?php if (!empty($fleet_img_url)): ?>
                    <div class="book-cars-section__visual-card">
                        <div class="book-cars-section__visual-frame">
                            <img src="<?= esc_url($fleet_img_url); ?>"
                                alt="<?php esc_attr_e('Hoi An Travel Private Fleet', 'hoiantravel'); ?>"
                                loading="lazy" />
                            <div class="book-cars-section__visual-caption">
                                <span class="caption-tag"><?php esc_html_e('Private Fleet', 'hoiantravel'); ?></span>
                                <span class="caption-text"><?php esc_html_e('Sedan, SUV & Minivan Options', 'hoiantravel'); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Service Highlights -->
                <div class="book-cars-section__highlights-card">
                    <h3 class="highlights-title h5">
                        <?php esc_html_e('Private Transfer Service', 'hoiantravel'); ?>
                    </h3>
                    <ul class="highlights-list">
                        <li class="highlights-item">
                            <span class="highlights-item__icon" aria-hidden="true"><?= ha_svg_icon('check'); ?></span>
                            <div class="highlights-item__content">
                                <strong><?php esc_html_e('Door-to-Door Service:', 'hoiantravel'); ?></strong>
                                <span><?php esc_html_e('Direct pickup at Tien Sa, Chan May ports to Hoi An, Da Nang, Hue.', 'hoiantravel'); ?></span>
                            </div>
                        </li>
                        <li class="highlights-item">
                            <span class="highlights-item__icon" aria-hidden="true"><?= ha_svg_icon('check'); ?></span>
                            <div class="highlights-item__content">
                                <strong><?php esc_html_e('Modern Air-Conditioned Fleet:', 'hoiantravel'); ?></strong>
                                <span><?php esc_html_e('Clean, well-maintained vehicles suitable for solo travelers, couples, and groups.', 'hoiantravel'); ?></span>
                            </div>
                        </li>
                        <li class="highlights-item">
                            <span class="highlights-item__icon" aria-hidden="true"><?= ha_svg_icon('check'); ?></span>
                            <div class="highlights-item__content">
                                <strong><?php esc_html_e('Experienced Local Drivers:', 'hoiantravel'); ?></strong>
                                <span><?php esc_html_e('Professional local drivers familiar with Central Vietnam routes and seaport gates.', 'hoiantravel'); ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Direct Contact Card (if phone or email exists) -->
                <?php if (!empty($phone) || !empty($email)): ?>
                    <div class="book-cars-section__contact-card">
                        <h4 class="contact-title">
                            <?php esc_html_e('Need Direct Assistance?', 'hoiantravel'); ?>
                        </h4>
                        <div class="contact-links">
                            <?php if (!empty($phone) && !empty($tel_digits)): ?>
                                <a href="tel:<?= esc_attr($tel_digits); ?>" class="contact-link" aria-label="<?php esc_attr_e('Call hotline', 'hoiantravel'); ?>">
                                    <span class="contact-link__icon" aria-hidden="true"><?= ha_svg_icon('phone'); ?></span>
                                    <span class="contact-link__text"><?= esc_html($phone); ?></span>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty($email)): ?>
                                <a href="mailto:<?= esc_attr($email); ?>" class="contact-link" aria-label="<?php esc_attr_e('Send an email', 'hoiantravel'); ?>">
                                    <span class="contact-link__icon" aria-hidden="true"><?= ha_svg_icon('mail-check'); ?></span>
                                    <span class="contact-link__text"><?= esc_html($email); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Booking Card (58%) -->
            <div class="book-cars-section__right">
                <div class="book-cars-section__card">
                    <div class="book-cars-section__card-header">
                        <h3 class="card-title h4">
                            <?php esc_html_e('Reserve Your Private Port Transfer', 'hoiantravel'); ?>
                        </h3>
                        <p class="card-desc">
                            <?php esc_html_e('Please share your cruise arrival details and preferred schedule below.', 'hoiantravel'); ?>
                        </p>
                    </div>

                    <!-- Shortcode Execution Wrapper -->
                    <div class="book-cars-section__form-container">
                        <?php if (!empty($form)): ?>
                            <?= do_shortcode($form); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
