<?php
/**
 * Single Tour Hero Section
 *
 * Fully synchronized with Hoi An Travel Shared Hero System
 * Features: Indochine atmospheric lighting, breadcrumbs, taxonomy badge,
 * tour metadata chips (duration, guests, location, price), highlights & ratings.
 *
 * @package hoiantravel
 */

$title = get_the_title();
$image = get_the_post_thumbnail_url();
$image_id = get_post_thumbnail_id();
$alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
if (empty($alt)) {
    $alt = $title;
}
$has_image = !empty($image);

// Tour Custom Fields
$time_tour = get_field('time_tour');
$price_tour = get_field('price_tour');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

$paxs_tours = get_field('paxs_tours');
$min_pax = isset($paxs_tours['min']) ? $paxs_tours['min'] : '';
$max_pax = isset($paxs_tours['max']) ? $paxs_tours['max'] : '';

// Location data handling (ACF or Taxonomy fallback)
$location_tour = get_field('location_tour');
$tour_locations = get_the_terms(get_the_ID(), 'tour_locations');
$location_name = '';

if (!empty($location_tour)) {
    if (is_array($location_tour)) {
        $location_name = isset($location_tour['name']) ? $location_tour['name'] : (isset($location_tour['label']) ? $location_tour['label'] : '');
    } elseif (is_object($location_tour) && isset($location_tour->name)) {
        $location_name = $location_tour->name;
    } else {
        $location_name = (string) $location_tour;
    }
}
if (empty($location_name) && !empty($tour_locations) && !is_wp_error($tour_locations)) {
    $location_name = implode(', ', wp_list_pluck($tour_locations, 'name'));
}

// Features / Highlights
$features = get_field('features_tour');

// Tour Category / Type
$tour_types = get_the_terms(get_the_ID(), 'tour_cats');
$tour_type_name = ($tour_types && !is_wp_error($tour_types)) ? $tour_types[0]->name : '';
?>

<section class="ha-section hero-section-shared hero-section-shared--single-tour<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
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

            <!-- Tour Type / Category Badge -->
            <?php if (!empty($tour_type_name)): ?>
                <div class="hero-section-shared__badge-wrap">
                    <span class="ha-badge hero-tour-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= esc_html($tour_type_name); ?>
                    </span>
                </div>
            <?php endif; ?>

            <!-- Main Title -->
            <h1 class="ha-heading hero-section-shared__title">
                <?= esc_html($title); ?>
            </h1>

            <!-- Excerpt / Short Description -->
            <?php if (has_excerpt()): ?>
                <div class="hero-section-shared__desc hero-tour-desc">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <!-- Tour Metadata Bar (Time, Guests, Location, Price) -->
            <?php if ($time_tour || ($min_pax && $max_pax) || $location_name || $price_tour): ?>
                <div class="hero-tour-meta" role="list">
                    <?php if ($time_tour): ?>
                        <div class="hero-tour-meta__item" role="listitem">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('clock'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($time_tour); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($min_pax && $max_pax): ?>
                        <div class="hero-tour-meta__item" role="listitem">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('users'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($min_pax); ?> - <?= esc_html($max_pax); ?> <?= esc_html__('guests', 'ha'); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($location_name)): ?>
                        <div class="hero-tour-meta__item" role="listitem">
                            <span class="hero-tour-meta__icon" aria-hidden="true">
                                <?= ha_svg_icon('location'); ?>
                            </span>
                            <span class="hero-tour-meta__label"><?= esc_html($location_name); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($price_tour): ?>
                        <div class="hero-tour-meta__item hero-tour-meta__item--price" role="listitem">
                            <span class="hero-tour-meta__price-sub"><?= esc_html__('From', 'ha'); ?></span>
                            <span class="hero-tour-meta__price-val"><?= esc_html(ha_format_price($price_tour, $currency)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Features / Highlights -->
            <?php if (!empty($features)): ?>
                <div class="hero-tour-features">
                    <?php foreach ($features as $feature): ?>
                        <?php if (!empty($feature['item'])): ?>
                            <span class="hero-tour-features__item">
                                <span class="hero-tour-features__icon" aria-hidden="true">
                                    <?= ha_svg_icon('check'); ?>
                                </span>
                                <span class="hero-tour-features__text"><?= esc_html($feature['item']); ?></span>
                            </span>
                        <?php endif; ?>
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
