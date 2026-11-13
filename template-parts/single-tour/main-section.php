<?php
/**
 * Single Tour Main Section
 *
 * Distinctive Hoi An Travel Layout:
 * Preserves all 7 essential sections with clear identities and visual rhythm:
 * 1. Gallery (Visual Opening - called in single-tours.php)
 * 2. Overview (Editorial Typography)
 * 3. Highlights (Curated Heritage Experience Points)
 * 4. Inclusions (Transparent Two-Column Checklist)
 * 5. Itinerary (Detailed Journey Timeline)
 * 6. Reviews (Guest Feedback & Ratings)
 * 7. FAQs (Global Accordion Support)
 * Accompanied by Boutique Sticky Booking Concierge Card & Mobile Floating CTA.
 *
 * @package hoiantravel
 */

$booking_form = get_field('form_booking', 'option');
$included_tour = get_field('included_tour');
$excluded_tour = get_field('excluded_tour');
$itinerary_tour = get_field('itinerary_tour');
$highlights = get_field('highlights_tour');
$price_group = get_field('price_for_group_tour');
$price_group_child = get_field('child_price_group');
$price_private = get_field('price_for_private_tour');
$price_tour = get_field('price_tour');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
$faqs_tour = get_field('faqs_list_tour');

// Dynamic Navigation Items
$nav_items = [];
$nav_items['tour-overview'] = ['label' => __('Overview', 'ha'), 'show' => !empty(get_the_content())];
$nav_items['tour-highlights'] = ['label' => __('Highlights', 'ha'), 'show' => !empty($highlights)];
$nav_items['tour-inclusions'] = ['label' => __('Inclusions', 'ha'), 'show' => (!empty($included_tour) || !empty($excluded_tour))];
$nav_items['tour-itinerary'] = ['label' => __('Itinerary', 'ha'), 'show' => !empty($itinerary_tour)];
$nav_items['tour-review'] = ['label' => __('Reviews', 'ha'), 'show' => (comments_open() || get_comments_number() > 0)];
$nav_items['tour-faqs'] = ['label' => __('FAQs', 'ha'), 'show' => !empty($faqs_tour)];
?>

<section class="ha-section single-tour-content-section" id="tour-details-section">
    <div class="container">

        <!-- In-Page Sticky Anchor Navigation Bar -->
        <?php if (count(array_filter(array_column($nav_items, 'show'))) > 1): ?>
            <nav class="single-tour-nav" id="ha-anchor-nav"
                aria-label="<?php esc_attr_e('Tour section navigation', 'ha'); ?>">
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

        <!-- Asymmetrical 2-Column Layout (Content Story + Sticky Concierge) -->
        <div class="single-tour-layout">

            <!-- Primary Content Column -->
            <div class="single-tour-main-col">

                <!-- 2. SECTION: OVERVIEW (Editorial Prose) -->
                <?php if (!empty(get_the_content())): ?>
                    <article id="tour-overview" class="tour-section-block tour-overview-editorial">
                        <header class="tour-section-header">
                            <h2 class="ha-heading tour-section-title h3">
                                <?php esc_html_e('Tour Overview', 'ha'); ?>
                            </h2>
                        </header>
                        <div class="tour-overview-editorial__body single-tour-prose">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endif; ?>

                <!-- 3. SECTION: HIGHLIGHTS (Curated Experience Points) -->
                <?php if (!empty($highlights)): ?>
                    <section id="tour-highlights" class="tour-section-block tour-heritage-highlights">
                        <header class="tour-heritage-highlights__header">
                            <span class="tour-heritage-highlights__icon" aria-hidden="true">
                                <?= ha_svg_icon('feature-lantern'); ?>
                            </span>
                            <div class="tour-heritage-highlights__titles">
                                <h2 class="ha-heading tour-section-title h3">
                                    <?php esc_html_e('Tour Highlights', 'ha'); ?>
                                </h2>
                            </div>
                        </header>
                        <div class="tour-heritage-highlights__content single-tour-prose">
                            <?= wp_kses_post($highlights); ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 4. SECTION: INCLUSIONS & EXCLUSIONS (Transparent Package Board) -->
                <?php if (!empty($included_tour) || !empty($excluded_tour)): ?>
                    <section id="tour-inclusions" class="tour-section-block tour-package-board">
                        <header class="tour-section-header">
                            <h2 class="ha-heading tour-section-title h3">
                                <?php esc_html_e("What's Included & Excluded", 'ha'); ?>
                            </h2>
                        </header>

                        <div class="tour-package-board__grid">
                            <!-- Included Checklist -->
                            <?php if (!empty($included_tour)): ?>
                                <div class="tour-package-column tour-package-column--included">
                                    <div class="tour-package-column__head">
                                        <span class="tour-package-column__icon" aria-hidden="true">
                                            <?= ha_svg_icon('check'); ?>
                                        </span>
                                        <h3 class="tour-package-column__title">
                                            <?php esc_html_e("What's Included", 'ha'); ?>
                                        </h3>
                                    </div>
                                    <ul class="tour-package-column__list">
                                        <?php foreach ($included_tour as $item):
                                            $text = is_array($item) ? current($item) : $item;
                                            if (empty($text)) {
                                                continue;
                                            }
                                            ?>
                                            <li class="tour-package-column__item">
                                                <span class="tour-package-column__bullet" aria-hidden="true">
                                                    <?= ha_svg_icon('check'); ?>
                                                </span>
                                                <span class="tour-package-column__text"><?php echo esc_html($text); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <!-- Excluded Checklist -->
                            <?php if (!empty($excluded_tour)): ?>
                                <div class="tour-package-column tour-package-column--excluded">
                                    <div class="tour-package-column__head">
                                        <span class="tour-package-column__icon" aria-hidden="true">
                                            <?= ha_svg_icon('menu-close-icon'); ?>
                                        </span>
                                        <h3 class="tour-package-column__title">
                                            <?php esc_html_e('Not Included / Optional', 'ha'); ?>
                                        </h3>
                                    </div>
                                    <ul class="tour-package-column__list">
                                        <?php foreach ($excluded_tour as $item):
                                            $text = is_array($item) ? current($item) : $item;
                                            if (empty($text)) {
                                                continue;
                                            }
                                            ?>
                                            <li class="tour-package-column__item">
                                                <span class="tour-package-column__bullet" aria-hidden="true">
                                                    <?= ha_svg_icon('menu-close-icon'); ?>
                                                </span>
                                                <span class="tour-package-column__text"><?php echo esc_html($text); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- 5. SECTION: ITINERARY (Detailed Journey Timeline) -->
                <?php get_template_part('template-parts/single-tour/itinerary-section'); ?>

                <!-- 6. SECTION: REVIEWS (Guest Stories & Social Proof) -->
                <?php get_template_part('template-parts/single-tour/review-section'); ?>

                <!-- 7. SECTION: FAQS (Practical Accordion Guide) -->
                <?php get_template_part('template-parts/single-tour/faqs-section'); ?>

            </div>

            <!-- Sticky Sidebar Column (Boutique Concierge Booking Card) -->
            <aside class="single-tour-sidebar-col">
                <div class="single-tour-sidebar-sticky">
                    <?php get_template_part('template-parts/single-tour/form-booking'); ?>
                </div>
            </aside>

        </div>
    </div>

    <!-- Mobile Floating Bottom Action Bar -->
    <div class="single-tour-mobile-bottom-bar d-lg-none" aria-label="<?php esc_attr_e('Quick booking bar', 'ha'); ?>">
        <div class="single-tour-mobile-bottom-bar__inner">
            <div class="single-tour-mobile-bottom-bar__price">
                <span class="label"><?php esc_html_e('From:', 'ha'); ?></span>
                <span class="value">
                    <?= esc_html(ha_format_price($price_tour ? $price_tour : 100, $currency)); ?>
                </span>
                <?php
                $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
                $is_private_only = empty($price_group) && !empty($price_private);
                if (!$is_private_only && !$is_car_tour):
                    ?>
                    <span class="unit">/ <?php esc_html_e('person', 'ha'); ?></span>
                <?php endif; ?>
            </div>

            <a href="#form-booking-tour" class="ha-button single-tour-mobile-bottom-bar__cta"
                aria-label="<?php esc_attr_e('Book Tour Now', 'ha'); ?>">
                <span><?php esc_html_e('Check Dates', 'ha'); ?></span>
                <?= ha_svg_icon('arrow-right'); ?>
            </a>
        </div>
    </div>
</section>