<?php
/**
 * Contact Page Map Section — Hoi An Travel
 *
 * Visual Focal Point: Fully responsive Google Maps embed displaying the central hub & customer station.
 * Responsive Treatment: Fluid wrapper controlling iframe aspect-ratio/height without layout shift or control cropping.
 *
 * @package hoiantravel
 */

// 1. Retrieve Map embed data directly from $map
$map = get_field('iframe_map');
// if (empty($map)) {
//     $map = get_field('map');
// }
// if (empty($map)) {
//     $map = get_field('iframe_map', 'option') ?: get_field('map', 'option');
// }

// Fallback to Google Maps Embed using office address if field is not configured
if (empty($map)) {
    $address = get_field('address', 'option') ?: '228 Nguyen Duy Hieu, Hoi An & Da Nang, Vietnam';
    $map_url = add_query_arg(
        array(
            'q' => urlencode($address),
            'output' => 'embed',
        ),
        'https://maps.google.com/maps'
    );
    $map = sprintf(
        '<iframe src="%s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="%s"></iframe>',
        esc_url($map_url),
        esc_attr__('Hoi An Travel Office Location Map', 'hoiantravel')
    );
}

// 2. Ensure iframe has loading="lazy" for performance & title for accessibility
if (!empty($map) && is_string($map)) {
    if (!str_contains($map, 'loading=')) {
        $map = str_replace('<iframe', '<iframe loading="lazy"', $map);
    }
    if (!str_contains($map, 'title=')) {
        $map = str_replace('<iframe', '<iframe title="' . esc_attr__('Google Maps Location - Hoi An Travel', 'hoiantravel') . '"', $map);
    }
    if (!str_contains($map, 'referrerpolicy=')) {
        $map = str_replace('<iframe', '<iframe referrerpolicy="no-referrer-when-downgrade"', $map);
    }
}

// Section Header Data
$heading = get_field('heading_map_contact') ?: __('Find Us in Central Vietnam', 'hoiantravel');
$desc = get_field('desc_map_contact') ?: __('Visit our office in Hoi An Ancient Town or connect with our private drivers across Da Nang, Hue and central heritage destinations.', 'hoiantravel');
$address = get_field('address', 'option') ?: '228 Nguyen Duy Hieu, Hoi An & Da Nang, Vietnam';
$open_time = get_field('open_time', 'option') ?: '07:00 AM – 10:00 PM (Daily, GMT+7)';
?>

<?php if (!empty($map)): ?>
    <section class="ha-section contact-map-section" id="contact-map-section">
        <div class="container">
            <!-- Section Header -->
            <div class="contact-map__header text-center mx-auto">
                <span class="ha-badge mb-3">
                    <?= ha_svg_icon('location'); ?>
                    <span><?php esc_html_e('Our Locations & Directions', 'hoiantravel'); ?></span>
                </span>
                <h2 class="contact-map__heading">
                    <?= esc_html($heading); ?>
                </h2>
                <?php if (!empty($desc)): ?>
                    <p class="contact-map__desc">
                        <?= esc_html($desc); ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Map Container & Visual Frame -->
            <div class="contact-map__wrapper">
                <div class="contact-map__frame">
                    <?= $map; ?>
                </div>

                <!-- Location Quick Card (Floating on desktop / cleanly positioned on mobile) -->
                <div class="contact-map__info-card">
                    <div class="contact-map__info-header">
                        <span class="info-badge-dot"></span>
                        <span class="info-badge-text"><?php esc_html_e('Hoi An & Da Nang Hub', 'hoiantravel'); ?></span>
                    </div>
                    <div class="contact-map__info-body">
                        <div class="info-item">
                            <span class="info-icon" aria-hidden="true"><?= ha_svg_icon('location'); ?></span>
                            <div class="info-text">
                                <strong><?php esc_html_e('Office & Customer Station', 'hoiantravel'); ?></strong>
                                <span><?= esc_html($address); ?></span>
                            </div>
                        </div>
                        <div class="info-item">
                            <span class="info-icon" aria-hidden="true"><?= ha_svg_icon('clock'); ?></span>
                            <div class="info-text">
                                <strong><?php esc_html_e('Operating Hours', 'hoiantravel'); ?></strong>
                                <span><?= esc_html($open_time); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="contact-map__info-footer">
                        <a href="https://maps.google.com/?q=<?= urlencode($address); ?>" target="_blank"
                            rel="noopener noreferrer" class="contact-map__directions-btn">
                            <span><?php esc_html_e('Get Directions on Google Maps', 'hoiantravel'); ?></span>
                            <?= ha_svg_icon('arrow-right'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>