<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$email = get_field('email', 'option');
$phone = get_field('phone', 'option');
$socials = get_field('socials', 'option');
$concierge_badge = get_field('footer__concierge_badge', 'option');
$concierge_heading = get_field('footer__concierge_heading', 'option');
$locations = get_field('footer_locations', 'option');
$license = get_field('footer_license', 'option');
if (empty($license)) {
    $license = 'Managed by VM Travel • International License: 46-028/2015/TCDL-GP LHQ';
}
$copyright = get_field('footer_copyright', 'option');
if (empty($copyright)) {
    $copyright = get_field('copyright_ft', 'option');
}
if (empty($copyright)) {
    $copyright = '© {{Year}} Hoi An Travel. All rights reserved.';
}
if (strpos($copyright, '{{Year}}') !== false) {
    $copyright = str_replace('{{Year}}', wp_date('Y'), $copyright);
}
?>

<footer class="main-footer">
    <!-- Vignette and warm lantern glow effects -->
    <div class="main-footer__glow-gradient" aria-hidden="true"></div>
    <div class="main-footer__glow-lantern" aria-hidden="true"></div>
    <div class="container">
        <!-- 1. THE GRAND INVITATION & DIRECT CONCIERGE BAR -->
        <div class="main-footer__concierge">
            <div class="main-footer__concierge-glow" aria-hidden="true"></div>
            <div class="main-footer__concierge-content">
                <?php if (!empty($concierge_badge)): ?>
                    <span class="main-footer__concierge-tag">
                        <?= $concierge_badge ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($concierge_heading)): ?>
                    <h2 class="main-footer__concierge-title h3">
                        <?= $concierge_heading ?>
                    </h2>
                <?php endif; ?>
                <div class="main-footer__concierge-actions">
                    <a href="https://wa.me/84901971515" class="main-footer__btn main-footer__btn--whatsapp">
                        <span class="main-footer__btn-icon"
                            aria-hidden="true"><?php echo ha_svg_icon('whatsapp'); ?></span>
                        WhatsApp 24/7
                    </a>
                    <a href="mailto:vmtravelhoian@gmail.com" class="main-footer__btn main-footer__btn--email">
                        <span class="main-footer__btn-icon"
                            aria-hidden="true"><?php echo ha_svg_icon('email'); ?></span>
                        Email Us
                    </a>
                </div>
            </div>
        </div>
        <!-- 2. 3-CITY REGIONAL STATIONS -->
        <?php if (!empty($locations)): ?>
            <div class="main-footer__stations">
                <?php foreach ($locations as $key => $location): ?>
                    <div class="station-card">
                        <h3 class="station-card__title h6">
                            <?= $location['name'] ?>
                        </h3>

                        <p class="station-card__address">
                            <?= $location['address'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <!-- 3. BRAND SIGNATURE & TRUST CREDENTIALS (SUB-FOOTER) -->
        <div class="main-footer__bottom">
            <!-- Left Zone (Brand & License Info) -->
            <div class="main-footer__brand">
                <div class="main-footer__logo">
                    <a class="d-flex " href="<?php echo home_url(); ?>"
                        aria-label="<?php echo get_bloginfo('name'); ?>">
                        <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>" width="200"
                            height="54" />
                    </a>
                </div>

                <?php if (!empty($license)): ?>
                    <span class="main-footer__license">
                        <?= $license ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Center Zone (Circular Social Icons) -->
            <div class="main-footer__socials">
                <?php if (!empty($socials)): ?>
                    <?php foreach ($socials as $key => $social): ?>
                        <a href="<?= esc_url($social['link']); ?>" class="main-footer__social-btn" target="_blank"
                            rel="noopener noreferrer" aria-label="Social Link <?= $key + 1; ?>">
                            <?php if (!empty($social['icon'])): ?>
                                <img src="<?= esc_url($social['icon']); ?>" alt="" width="16" height="16" />
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>


            <div class="main-footer__right">
                <div class="main-footer__payments">
                    <span class="main-footer__payment-badge">OnePay</span>
                    <span class="main-footer__payment-badge">VISA</span>
                    <span class="main-footer__payment-badge">MasterCard</span>
                </div>
                <?php if (!empty($copyright)): ?>
                    <p class="main-footer__copyright">
                        <?= $copyright ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>