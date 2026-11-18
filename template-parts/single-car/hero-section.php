<?php
/**
 * Single Car Hero Section
 *
 * Fully synchronized with Hoi An Travel Shared Hero System
 * Features: Indochine atmospheric lighting, breadcrumbs pill, car service badge,
 * route & car metadata chips (route/location, duration, capacity, pickup type, starting price),
 * trust feature chips, and star ratings.
 *
 * @package hoiantravel
 */

$title     = get_the_title();
$image     = get_the_post_thumbnail_url(get_the_ID(), 'full');
$image_id  = get_post_thumbnail_id();
$alt       = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
if (empty($alt)) {
    $alt = $title;
}
$has_image = !empty($image);

// Car Custom Fields
$location  = get_field('location_car');
$time      = get_field('time_car');
$pick_up   = get_field('pick_up_car');
$price     = get_field('price_car');
$currency  = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
$options   = get_field('option_car');

// Calculate lowest price if price_car is empty
if (empty($price) && !empty($options) && is_array($options)) {
    $prices = array_filter(array_map(function ($opt) {
        $clean = str_replace(',', '', (string) ($opt['price'] ?? ''));
        return is_numeric($clean) ? floatval($clean) : 0;
    }, $options));
    if (!empty($prices)) {
        $price = min($prices);
    }
}

// Car Category / Taxonomy Badge
$car_types = get_the_terms(get_the_ID(), 'car_category');
$car_type_name = ($car_types && !is_wp_error($car_types)) ? $car_types[0]->name : '';
if (empty($car_type_name)) {
    $car_type_name = __('Hoi An Private Car Service', 'hoiantravel');
}

// Key Service Highlights for Private Car Transfers
$car_highlights = [
    __('Private Air-Conditioned Vehicle', 'hoiantravel'),
    __('English-Speaking Driver', 'hoiantravel'),
    __('All Tolls & Fees Included', 'hoiantravel'),
    __('Door-to-Door Service', 'hoiantravel'),
];
?>

<section class="ha-section hero-section-shared hero-section-shared--single-tour hero-section-shared--single-car<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
    <!-- Background Image & Atmospheric Indochine Layers -->
    <div class="ha-section__bg hero-section-shared__bg">
        <?php if ($has_image): ?>
            <img src="<?= esc_url($image); ?>" alt="<?= esc_attr($alt); ?>" loading="eager" />
        <?php else: ?>
            <div class="hero-section-shared__fallback-pattern" aria-hidden="true">
                <?= ha_svg_icon('about-watermark'); ?>
            </div>
        <?php endif; ?>
        <div class="hero-section-shared__overlay"></div>
        <div class="hero-section-shared__glow"></div>
    </div>

    <!-- Main Content Container -->
    <div class="container hero-section-shared__container">
        <div class="hero-section-shared__box">

            <!-- Breadcrumbs -->
            <div class="hero-section-shared__breadcrumbs-wrap">
                <?php ha_breadcrumbs($title); ?>
            </div>

            <!-- Car Service / Category Badge -->
            <div class="hero-section-shared__badge-wrap">
                <span class="ha-badge ha-badge--on-dark hero-tour-badge hero-car-badge">
                    <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('car-simple'); ?></span>
                    <span class="ha-badge__text"><?= esc_html($car_type_name); ?></span>
                </span>
            </div>

            <!-- Main Title -->
            <h1 class="ha-heading hero-section-shared__title">
                <?= esc_html($title); ?>
            </h1>

            <!-- Excerpt / Short Description -->
            <?php if (has_excerpt()): ?>
                <div class="hero-section-shared__desc hero-tour-desc hero-car-desc">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <!-- Car Metadata Chips (Route, Duration, Capacity, Pickup, Price) -->
            <?php if (!empty($location) || !empty($time) || !empty($pick_up) || !empty($price) || !empty($options)): ?>
                <div class="hero-tour-meta hero-car-meta" role="list">
                    <?php if (!empty($location)): ?>
                        <div class="hero-tour-meta__item" role="listitem" title="<?php esc_attr_e('Transfer Route', 'hoiantravel'); ?>">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('location'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($location); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($time)): ?>
                        <div class="hero-tour-meta__item" role="listitem" title="<?php esc_attr_e('Travel Duration', 'hoiantravel'); ?>">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('clock'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($time); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="hero-tour-meta__item" role="listitem" title="<?php esc_attr_e('Available Vehicles', 'hoiantravel'); ?>">
                        <span class="hero-tour-meta__icon" aria-hidden="true">
                            <?= ha_svg_icon('users'); ?>
                        </span>
                        <span class="hero-tour-meta__label"><?php esc_html_e('Sedan (4), SUV (7), Van (16)', 'hoiantravel'); ?></span>
                    </div>

                    <?php if (!empty($pick_up)): ?>
                        <div class="hero-tour-meta__item" role="listitem" title="<?php esc_attr_e('Pickup Service', 'hoiantravel'); ?>">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('check-circle'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($pick_up); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($price)): ?>
                        <div class="hero-tour-meta__item hero-tour-meta__item--price" role="listitem" title="<?php esc_attr_e('Starting Price', 'hoiantravel'); ?>">
                            <span class="hero-tour-meta__price-sub"><?= esc_html__('From', 'hoiantravel'); ?></span>
                            <span class="hero-tour-meta__price-val"><?= esc_html(ha_format_price($price, $currency)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Trust / Service Highlights -->
            <?php if (!empty($car_highlights)): ?>
                <div class="hero-tour-features hero-car-features">
                    <?php foreach ($car_highlights as $highlight): ?>
                        <span class="hero-tour-features__item">
                            <span class="hero-tour-features__icon" aria-hidden="true">
                                <?= ha_svg_icon('check'); ?>
                            </span>
                            <span class="hero-tour-features__text"><?= esc_html($highlight); ?></span>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Star Ratings -->
            <?php if (function_exists('kk_star_ratings')): ?>
                <div class="hero-section-shared__ratings">
                    <?= kk_star_ratings(); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Decorative Indochine Bottom Ribbon -->
    <div class="hero-section-shared__ribbon" aria-hidden="true">
        <?= ha_svg_icon('indochine-ribbon'); ?>
    </div>
</section>
