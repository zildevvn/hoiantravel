<?php
/**
 * Single Car Main Section
 *
 * 2-Column layout: Content Story (Left) + Sticky Concierge Card (Right)
 *
 * @package hoiantravel
 */

$location = get_field('location_car');
$time     = get_field('time_car');
$pick_up  = get_field('pick_up_car');
$options  = get_field('option_car');

// Highlights data
$highlights_car = get_field('highlights_car');
$has_highlights = !empty($highlights_car) && !empty(trim(strip_tags($highlights_car)));

// Included & Excluded data (Dynamic from ACF)
$included_car = get_field('included_car');
$valid_included = [];
if (!empty($included_car) && is_array($included_car)) {
    foreach ($included_car as $item) {
        $text = is_array($item) ? ($item['excluded_item'] ?? ($item['included_item'] ?? current($item))) : $item;
        $text = is_string($text) ? trim($text) : '';
        if (!empty($text)) {
            $valid_included[] = $text;
        }
    }
}

$excluded_car = get_field('excluded_car');
$valid_excluded = [];
if (!empty($excluded_car) && is_array($excluded_car)) {
    foreach ($excluded_car as $item) {
        $text = is_array($item) ? ($item['excluded_item'] ?? ($item['included_item'] ?? current($item))) : $item;
        $text = is_string($text) ? trim($text) : '';
        if (!empty($text)) {
            $valid_excluded[] = $text;
        }
    }
}
$has_inclusions = !empty($valid_included) || !empty($valid_excluded);

// Check Itinerary data exists
$itinerary = get_field('itinerary_car');
$has_itinerary = false;
if (!empty($itinerary) && is_array($itinerary)) {
    $enable_para = !empty($itinerary['enable_itinerary_paragraph']);
    $timeline    = (!empty($itinerary['itinerary_list']) && is_array($itinerary['itinerary_list'])) ? $itinerary['itinerary_list'] : [];
    $group_para  = $itinerary['itinerary_paragraph'] ?? ($itinerary['itinerary_desc'] ?? '');
    $img         = $itinerary['img'] ?? '';
    if (!empty($timeline) || ($enable_para && !empty($group_para)) || !empty($img)) {
        $has_itinerary = true;
    }
}

// Check FAQs data exists
$faqs_car = get_field('faqs_list_car');
$has_faqs = false;
if (!empty($faqs_car) && is_array($faqs_car)) {
    foreach ($faqs_car as $f) {
        if (!empty($f['question']) || !empty($f['answer'])) {
            $has_faqs = true;
            break;
        }
    }
}

// Dynamic Navigation Items for Single Car
$nav_items = [];
$nav_items['car-overview']   = ['label' => __('Overview', 'hoiantravel'), 'show' => !empty(get_the_content())];
$nav_items['car-highlights'] = ['label' => __('Highlights', 'hoiantravel'), 'show' => $has_highlights];
$nav_items['car-fleet']      = ['label' => __('Fleet Options', 'hoiantravel'), 'show' => true];
$nav_items['car-inclusions'] = ['label' => __('Inclusions', 'hoiantravel'), 'show' => $has_inclusions];
$nav_items['car-itinerary']  = ['label' => __('Itinerary', 'hoiantravel'), 'show' => $has_itinerary];
$nav_items['car-review']     = ['label' => __('Reviews', 'hoiantravel'), 'show' => (comments_open() || get_comments_number() > 0)];
$nav_items['car-faqs']       = ['label' => __('FAQs', 'hoiantravel'), 'show' => $has_faqs];
?>

<section class="ha-section single-car-content-section" id="car-details-section">
    <div class="container">

        <!-- In-Page Sticky Anchor Navigation Bar -->
        <?php if (count(array_filter(array_column($nav_items, 'show'))) > 1): ?>
            <nav class="single-tour-nav ha-anchor-nav" id="ha-anchor-nav"
                aria-label="<?php esc_attr_e('Car section navigation', 'hoiantravel'); ?>">
                <div class="single-tour-nav__inner">
                    <ul class="single-tour-nav__list">
                        <?php foreach ($nav_items as $id => $item):
                            if (!$item['show']) {
                                continue;
                            }
                            ?>
                            <li class="single-tour-nav__item">
                                <a href="#<?php echo esc_attr($id); ?>" class="single-tour-nav__link">
                                    <?php echo esc_html($item['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>
        <?php endif; ?>

        <div class="single-tour-layout single-car-layout">

            <!-- Primary Content Column -->
            <div class="single-tour-main-col single-car-main-col">

                <!-- 1. SECTION: OVERVIEW -->
                <?php if (!empty(get_the_content())): ?>
                    <article id="car-overview" class="tour-section-block car-overview-block">
                        <header class="tour-section-header">
                            <h2 class="ha-heading tour-section-title h3">
                                <?php esc_html_e('Overview', 'hoiantravel'); ?>
                            </h2>
                        </header>
                        <div class="tour-overview-editorial__body single-tour-prose">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endif; ?>

                <!-- 2. SECTION: HIGHLIGHTS (Curated Experience Points) -->
                <?php if ($has_highlights): ?>
                    <section id="car-highlights" class="tour-section-block tour-heritage-highlights car-highlights-block">
                        <header class="tour-heritage-highlights__header">
                            <span class="tour-heritage-highlights__icon" aria-hidden="true">
                                <?= ha_svg_icon('feature-lantern'); ?>
                            </span>
                            <div class="tour-heritage-highlights__titles">
                                <h2 class="ha-heading tour-section-title h3">
                                    <?php esc_html_e('Highlights', 'hoiantravel'); ?>
                                </h2>
                            </div>
                        </header>
                        <div class="tour-heritage-highlights__content single-tour-prose">
                            <?= wp_kses_post($highlights_car); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 3. SECTION: FLEET SPECIFICATIONS -->
                <section id="car-fleet" class="tour-section-block car-fleet-block">
                    <header class="tour-section-header">
                        <h2 class="ha-heading tour-section-title h3">
                            <?php esc_html_e('Our Modern Fleet Options', 'hoiantravel'); ?>
                        </h2>
                        <p class="text-muted">
                            <?php esc_html_e('Every vehicle is sanitized, air-conditioned, and maintained under strict safety standards with a dedicated professional driver.', 'hoiantravel'); ?>
                        </p>
                    </header>

                    <div class="car-fleet-cards">
                        <!-- Sedan -->
                        <div class="car-fleet-card">
                            <div class="car-fleet-card__icon">
                                <?= ha_svg_icon('car-4-seat'); ?>
                            </div>
                            <div class="car-fleet-card__content">
                                <h3 class="h5 mb-1"><?php esc_html_e('Standard Sedan (4-Seater)', 'hoiantravel'); ?>
                                </h3>
                                <p class="text-muted small mb-2">
                                    <?php esc_html_e('Toyota Vios, Honda City, Hyundai Accent or equivalent', 'hoiantravel'); ?>
                                </p>
                                <ul class="car-fleet-card__specs">
                                    <li><?= ha_svg_icon('users'); ?>
                                        <?php esc_html_e('Capacity: Up to 4 passengers', 'hoiantravel'); ?></li>
                                    <li><?= ha_svg_icon('car-simple'); ?>
                                        <?php esc_html_e('Luggage: 2 suitcases (24") + 2 hand bags', 'hoiantravel'); ?>
                                    </li>
                                    <li><?= ha_svg_icon('check-simple'); ?>
                                        <?php esc_html_e('Optimal for couples or small families', 'hoiantravel'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- SUV -->
                        <div class="car-fleet-card">
                            <div class="car-fleet-card__icon">
                                <?= ha_svg_icon('car-7-seat'); ?>
                            </div>
                            <div class="car-fleet-card__content">
                                <h3 class="h5 mb-1"><?php esc_html_e('Spacious SUV (7-Seater)', 'hoiantravel'); ?></h3>
                                <p class="text-muted small mb-2">
                                    <?php esc_html_e('Toyota Innova, Toyota Fortuner, Mitsubishi Xpander or equivalent', 'hoiantravel'); ?>
                                </p>
                                <ul class="car-fleet-card__specs">
                                    <li><?= ha_svg_icon('users'); ?>
                                        <?php esc_html_e('Capacity: Up to 7 passengers', 'hoiantravel'); ?></li>
                                    <li><?= ha_svg_icon('car-simple'); ?>
                                        <?php esc_html_e('Luggage: 4-5 suitcases (24") + daypacks', 'hoiantravel'); ?>
                                    </li>
                                    <li><?= ha_svg_icon('check-simple'); ?>
                                        <?php esc_html_e('High ground clearance & elevated view', 'hoiantravel'); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Van -->
                        <div class="car-fleet-card">
                            <div class="car-fleet-card__icon">
                                <?= ha_svg_icon('car-16-seat'); ?>
                            </div>
                            <div class="car-fleet-card__content">
                                <h3 class="h5 mb-1"><?php esc_html_e('Minivan (16-Seater)', 'hoiantravel'); ?></h3>
                                <p class="text-muted small mb-2">
                                    <?php esc_html_e('Ford Transit, Hyundai Solati or equivalent', 'hoiantravel'); ?>
                                </p>
                                <ul class="car-fleet-card__specs">
                                    <li><?= ha_svg_icon('users'); ?>
                                        <?php esc_html_e('Capacity: Up to 16 passengers', 'hoiantravel'); ?></li>
                                    <li><?= ha_svg_icon('car-simple'); ?>
                                        <?php esc_html_e('Luggage: 10-12 large suitcases', 'hoiantravel'); ?></li>
                                    <li><?= ha_svg_icon('check-simple'); ?>
                                        <?php esc_html_e('Spacious aisle & high headroom', 'hoiantravel'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4. SECTION: INCLUSIONS & EXCLUSIONS (Dynamic from ACF) -->
                <?php if ($has_inclusions): ?>
                    <section id="car-inclusions" class="tour-section-block tour-heritage-inclusions car-inclusions-block">
                        <header class="tour-section-header">
                            <h2 class="ha-heading tour-section-title h3">
                                <?php
                                if (!empty($valid_included) && !empty($valid_excluded)) {
                                    esc_html_e("What's Included & Excluded", 'hoiantravel');
                                } elseif (!empty($valid_included)) {
                                    esc_html_e("What's Included", 'hoiantravel');
                                } else {
                                    esc_html_e('Not Included / Optional', 'hoiantravel');
                                }
                                ?>
                            </h2>
                        </header>

                        <div class="tour-package-comparison">
                            <!-- Included Checklist -->
                            <?php if (!empty($valid_included)): ?>
                                <div class="tour-package-column tour-package-column--included">
                                    <div class="tour-package-column__header">
                                        <span class="tour-package-column__icon" aria-hidden="true">
                                            <?= ha_svg_icon('check-circle'); ?>
                                        </span>
                                        <h3 class="tour-package-column__title">
                                            <?php esc_html_e('Included with Every Booking', 'hoiantravel'); ?>
                                        </h3>
                                    </div>
                                    <ul class="tour-package-column__list">
                                        <?php foreach ($valid_included as $text): ?>
                                            <li class="tour-package-column__item">
                                                <span class="tour-package-column__bullet" aria-hidden="true"><?= ha_svg_icon('check-simple'); ?></span>
                                                <span class="tour-package-column__text"><?php echo esc_html($text); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- Excluded Checklist -->
                            <?php if (!empty($valid_excluded)): ?>
                                <div class="tour-package-column tour-package-column--excluded">
                                    <div class="tour-package-column__header">
                                        <span class="tour-package-column__icon" aria-hidden="true">
                                            <?= ha_svg_icon('menu-close-icon'); ?>
                                        </span>
                                        <h3 class="tour-package-column__title">
                                            <?php esc_html_e('Not Included / Optional', 'hoiantravel'); ?>
                                        </h3>
                                    </div>
                                    <ul class="tour-package-column__list">
                                        <?php foreach ($valid_excluded as $text): ?>
                                            <li class="tour-package-column__item">
                                                <span class="tour-package-column__bullet" aria-hidden="true"><?= ha_svg_icon('menu-close-icon'); ?></span>
                                                <span class="tour-package-column__text"><?php echo esc_html($text); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 4. SECTION: ITINERARY -->
                <?php get_template_part('template-parts/single-car/itinerary-section'); ?>

                <!-- 5. SECTION: REVIEWS -->
                <?php
                if (comments_open() || get_comments_number() > 0) {
                    if (file_exists(get_template_directory() . '/template-parts/single-tour/review-section.php')) {
                        get_template_part('template-parts/single-tour/review-section');
                    } else {
                        comments_template();
                    }
                }
                ?>

                <!-- 6. SECTION: FAQS -->
                <?php get_template_part('template-parts/single-car/faqs-section'); ?>

            </div>

            <!-- Sticky Sidebar Column (Concierge Booking Card) -->
            <aside class="single-tour-sidebar-col single-car-sidebar-col">
                <div class="single-tour-sidebar-sticky">
                    <?php get_template_part('template-parts/single-car/form-booking'); ?>
                </div>
            </aside>

        </div>
    </div>
</section>