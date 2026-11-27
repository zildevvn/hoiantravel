<?php
/**
 * Site Main Footer
 *
 * 3-Tier Editorial Travel Footer:
 * 1. Direct Concierge Bar (Badge, Heading, WhatsApp & Customized Tour CTAs)
 * 2. Asymmetric Main Grid (Brand/Contact/Socials + Dynamic WP Footer Menus + Regional Stations)
 * 3. Sub-Footer Bottom Bar (Copyright + Accepted Payments)
 *
 * @package hoiantravel
 */

$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = $custom_logo_id ? wp_get_attachment_url($custom_logo_id) : '';
$email = function_exists('get_field') ? get_field('email', 'option') : '';
$phone = function_exists('get_field') ? get_field('phone', 'option') : '';
$open_time = function_exists('get_field') ? get_field('open_time', 'option') : '';
$socials = function_exists('get_field') ? get_field('socials', 'option') : [];
$concierge_badge = function_exists('get_field') ? get_field('footer__concierge_badge', 'option') : '';
$concierge_heading = function_exists('get_field') ? get_field('footer__concierge_heading', 'option') : '';
$locations = function_exists('get_field') ? get_field('footer_locations', 'option') : [];
$license = function_exists('get_field') ? get_field('footer_license', 'option') : '';
if (empty($license)) {
    $license = 'Managed by VM Travel • International License: 46-028/2015/TCDL-GP LHQ';
}

$copyright = function_exists('get_field') ? get_field('footer_copyright', 'option') : '';
if (empty($copyright) && function_exists('get_field')) {
    $copyright = get_field('copyright_ft', 'option');
}
if (empty($copyright)) {
    $copyright = '© {{Year}} Hoi An Travel. All rights reserved.';
}
if (strpos($copyright, '{{Year}}') !== false) {
    $copyright = str_replace('{{Year}}', wp_date('Y'), $copyright);
}

$footer_menus = [
    'daily-tours-menu' => __('Daily Tours', 'hoiantravel'),
    'package-tours-menu' => __('Holiday Packages', 'hoiantravel'),
    'company-menu' => __('Company', 'hoiantravel'),
];

/**
 * TEMPORARY HARDCODED FALLBACK CONTENT
 * Used only when corresponding ACF Theme Options are not yet configured.
 * Replace or remove once Theme Options are populated in WP Admin.
 */
$temp_footer_content = [
    'concierge_badge' => __('24/7 Local Concierge', 'hoiantravel'),
    'concierge_heading' => __('Ready to craft your unforgettable Hoi An & Central Vietnam journey?', 'hoiantravel'),
    'brand_intro' => __('Handcrafted tours, private transfers, and local experiences in Hoi An & Central Vietnam.', 'hoiantravel'),
    'open_time' => __('Daily: 07:00 AM - 10:00 PM (GMT+7)', 'hoiantravel'),
];

if (empty($concierge_badge)) {
    $concierge_badge = $temp_footer_content['concierge_badge'];
}
if (empty($concierge_heading)) {
    $concierge_heading = $temp_footer_content['concierge_heading'];
}
if (empty($open_time)) {
    $open_time = $temp_footer_content['open_time'];
}

$brand_intro = $temp_footer_content['brand_intro'];
$phone_digits = !empty($phone) ? preg_replace('/\D/', '', (string) $phone) : '';
if ($phone_digits !== '' && str_starts_with($phone_digits, '0')) {
    $phone_digits = '84' . substr($phone_digits, 1);
}
$phone_href = ($phone_digits !== '') ? 'https://wa.me/' . $phone_digits : '';
?>

<footer class="main-footer">
    <!-- Subtle ambient lantern glow -->
    <div class="main-footer__glow-gradient" aria-hidden="true"></div>
    <div class="main-footer__glow-lantern" aria-hidden="true"></div>

    <div class="container">
        <!-- 1. DIRECT CONCIERGE BAR -->
        <div class="main-footer__concierge">
            <div class="main-footer__concierge-glow" aria-hidden="true"></div>
            <div class="main-footer__concierge-inner">
                <div class="main-footer__concierge-content">
                    <?php if (!empty($concierge_badge)): ?>
                        <span class="main-footer__concierge-tag">
                            <?= esc_html($concierge_badge); ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!empty($concierge_heading)): ?>
                        <h2 class="main-footer__concierge-title h3">
                            <?= wp_kses_post($concierge_heading); ?>
                        </h2>
                    <?php endif; ?>
                </div>

                <div class="main-footer__concierge-actions">
                    <a href="https://wa.me/84901971515" class="main-footer__btn main-footer__btn--whatsapp">
                        <span class="main-footer__btn-icon" aria-hidden="true"><?= ha_svg_icon('whatsapp'); ?></span>
                        <?php esc_html_e('WhatsApp 24/7', 'hoiantravel'); ?>
                    </a>
                    <a href="<?= esc_url(home_url('/hoi-an-holiday-packages/')); ?>"
                        class="main-footer__btn main-footer__btn--email">
                        <span class="main-footer__btn-icon"
                            aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                        <?php esc_html_e('Customized Tour', 'hoiantravel'); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. BALANCED MAIN FOOTER GRID (Brand + Navigation Columns) -->
        <div class="main-footer__main">
            <!-- Lean Brand Identity Column -->
            <div class="main-footer__brand">
                <?php if (!empty($logo_url)): ?>
                    <div class="main-footer__logo">
                        <a href="<?= esc_url(home_url('/')); ?>" aria-label="<?= esc_attr(get_bloginfo('name')); ?>">
                            <img src="<?= esc_url($logo_url); ?>" alt="<?= esc_attr(get_bloginfo('name')); ?>" width="180"
                                height="48" loading="lazy" />
                        </a>
                    </div>
                <?php else: ?>
                    <div class="main-footer__logo">
                        <a href="<?= esc_url(home_url('/')); ?>" class="main-footer__brand-name">
                            <?= esc_html(get_bloginfo('name')); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($brand_intro)): ?>
                    <p class="main-footer__brand-desc">
                        <?= esc_html($brand_intro); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Dynamic WordPress Footer Navigation Columns -->
            <nav class="main-footer__nav-cols" aria-label="<?php esc_attr_e('Footer navigation', 'hoiantravel'); ?>">
                <?php foreach ($footer_menus as $location => $menu_title): ?>
                    <div class="main-footer__nav-col">
                        <h3 class="main-footer__col-title"><?= esc_html($menu_title); ?></h3>
                        <?php
                        if (has_nav_menu($location)) {
                            wp_nav_menu([
                                'theme_location' => $location,
                                'container' => false,
                                'menu_class' => 'main-footer__menu',
                                'depth' => 1,
                                'fallback_cb' => false,
                            ]);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- 3. CONTACT & SOCIAL UTILITY ROW -->
        <?php if (!empty($phone) || !empty($email) || !empty($open_time) || (!empty($socials) && is_array($socials))): ?>
            <div class="main-footer__connect">
                <?php if (!empty($phone) || !empty($email) || !empty($open_time)): ?>
                    <ul class="main-footer__contact-list">
                        <?php if (!empty($phone) && !empty($phone_href)): ?>
                            <li>
                                <a href="<?= esc_url($phone_href); ?>" class="main-footer__contact-link" target="_blank"
                                    rel="noopener noreferrer">
                                    <span class="main-footer__contact-icon" aria-hidden="true"><?= ha_svg_icon('phone'); ?></span>
                                    <span><?= esc_html($phone); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($email)): ?>
                            <li>
                                <a href="mailto:<?= esc_attr(antispambot($email)); ?>" class="main-footer__contact-link">
                                    <span class="main-footer__contact-icon" aria-hidden="true"><?= ha_svg_icon('email'); ?></span>
                                    <span><?= esc_html(antispambot($email)); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($open_time)): ?>
                            <li class="main-footer__contact-item">
                                <span class="main-footer__contact-icon"
                                    aria-hidden="true"><?= ha_svg_icon('clock-circle'); ?></span>
                                <span><?= esc_html($open_time); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($socials) && is_array($socials)): ?>
                    <div class="main-footer__socials" aria-label="<?php esc_attr_e('Social media links', 'hoiantravel'); ?>">
                        <?php foreach ($socials as $key => $social):
                            $social_link = !empty($social['link']) ? $social['link'] : '';
                            $social_icon = !empty($social['icon']) ? $social['icon'] : '';
                            if (empty($social_link)) {
                                continue;
                            }
                            ?>
                            <a href="<?= esc_url($social_link); ?>" class="main-footer__social-btn" target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?= esc_attr(sprintf(__('Social Link %d', 'hoiantravel'), $key + 1)); ?>">
                                <?php if (!empty($social_icon)): ?>
                                    <img src="<?= esc_url($social_icon); ?>" alt="" width="16" height="16" loading="lazy" />
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- 3. REGIONAL STATIONS -->
        <?php if (!empty($locations) && is_array($locations)): ?>
            <div class="main-footer__stations">
                <?php foreach ($locations as $location):
                    $loc_name = !empty($location['name']) ? $location['name'] : '';
                    $loc_address = !empty($location['address']) ? $location['address'] : '';
                    if ($loc_name === '' && $loc_address === '') {
                        continue;
                    }
                    ?>
                    <div class="station-card">
                        <?php if ($loc_name !== ''): ?>
                            <h3 class="station-card__title h6">
                                <?= esc_html($loc_name); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($loc_address !== ''): ?>
                            <p class="station-card__address">
                                <?= esc_html($loc_address); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- 4. SUB-FOOTER BOTTOM BAR -->
        <div class="main-footer__bottom">
            <?php if (!empty($copyright)): ?>
                <p class="main-footer__copyright">
                    <?= esc_html($copyright); ?>
                </p>
            <?php endif; ?>

            <?php if (!empty($license)): ?>
                <p class="main-footer__license">
                    <?= wp_kses_post($license); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</footer>