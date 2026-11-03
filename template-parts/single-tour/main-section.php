<?php
$booking_form = get_field('form_booking', 'option');
$included_tour = get_field('included_tour');
$excluded_tour = get_field('excluded_tour');
$itinerary_tour = get_field('itinerary_tour');
$highlights = get_field('highlights_tour');
$price_group = get_field('price_for_group_tour');
$price_group_child = get_field('child_price_group');
$price_private = get_field('price_for_private_tour');
$gallery_tour = get_field('gallery_tour');
$price_tour = get_field('price_tour');
$faqs_tour = get_field('faqs_list_tour');
?>
<section class="vm-section main-section">
    <div class="container">
        <div class="main-section-wrap">
            <div class="main-section-content">
                <?php
                // Build array of menu items to display dynamically
                $nav_items = [];
                $nav_items['tour-overview'] = ['label' => __('Overview', 'vm'), 'show' => true];
                $nav_items['tour-highlights'] = ['label' => __('Highlights', 'vm'), 'show' => !empty($highlights) || !empty($gallery_tour)];
                $nav_items['tour-inclusions'] = ['label' => __('Inclusions', 'vm'), 'show' => (!empty($included_tour) || !empty($excluded_tour))];
                $nav_items['tour-prices'] = ['label' => __('Prices', 'vm'), 'show' => (!empty($price_group) || !empty($price_private))];
                $nav_items['tour-itinerary'] = ['label' => __('Itinerary', 'vm'), 'show' => !empty($itinerary_tour)];
                $nav_items['tour-review'] = ['label' => __('Reviews', 'vm'), 'show' => (comments_open() || get_comments_number() > 0)];
                $nav_items['tour-faqs'] = ['label' => __('FAQs', 'vm'), 'show' => !empty($faqs_tour)];
                ?>

                <!-- Anchor Navigation Menu -->
                <nav class="anchor-nav" id="vm-anchor-nav" aria-label="<?php esc_attr_e('Tour navigation', 'vm'); ?>">
                    <div class="anchor-nav__inner">
                        <ul class="anchor-nav__list">
                            <?php foreach ($nav_items as $id => $item):
                                if (!$item['show'])
                                    continue;
                                ?>
                                <li class="anchor-nav__item">
                                    <a href="#<?php echo esc_attr($id); ?>" class="anchor-nav__link">
                                        <?php echo esc_html($item['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </nav>

                <div class="main-section-content-wrapper">
                    <div id="tour-overview" class="tour-overview">
                        <h2 class="vm-heading ">Overview Tour</h2>
                        <div class="tour-overview__content">
                            <?php the_content() ?>
                        </div>
                    </div>

                    <?php if (!empty($highlights)): ?>
                        <div id="tour-highlights" class="tour-highlights">
                            <h2 class="vm-heading ">Tour Highlights</h2>
                            <div class="tour-highlights__content">
                                <?= !empty($highlights) ? $highlights : '' ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($included_tour) || !empty($excluded_tour)): ?>
                        <div id="tour-inclusions" class="tour-inclusions">
                            <div class="tour-inclusions__wrapper">
                                <?php if (!empty($included_tour)): ?>
                                    <div class="inclusion-card card-included">
                                        <h3>
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                            </svg>
                                            What's Included
                                        </h3>
                                        <ul>
                                            <?php foreach ($included_tour as $index => $item):
                                                $text = is_array($item) ? current($item) : $item;
                                                if (empty($text))
                                                    continue;
                                                ?>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-check-check-icon lucide-check-check">
                                                        <path d="M18 6 7 17l-5-5" />
                                                        <path d="m22 10-7.5 7.5L13 16" />
                                                    </svg>
                                                    <span>
                                                        <?php echo esc_html($text); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($excluded_tour)): ?>
                                    <div class="inclusion-card card-excluded">
                                        <h3>
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                            </svg>
                                            Not Included
                                        </h3>
                                        <ul>
                                            <?php foreach ($excluded_tour as $index => $item):
                                                $text = is_array($item) ? current($item) : $item;
                                                if (empty($text))
                                                    continue;
                                                ?>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-x-icon lucide-x">
                                                        <path d="M18 6 6 18" />
                                                        <path d="m6 6 12 12" />
                                                    </svg>
                                                    <span>
                                                        <?php echo esc_html($text); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php
                    get_template_part('template-parts/single-tour/itinerary-section');
                    get_template_part('template-parts/single-tour/review-section');
                    get_template_part('template-parts/single-tour/faqs-section');
                    ?>
                </div>
            </div>

            <div class="main-section__sidebar">
                <?php get_template_part('template-parts/single-tour/form-booking'); ?>
            </div>

            <div class="main-section__sidebar-mobile">
                <div class="main-section__sidebar-mobile-inner">

                    <div class="price">
                        <span class="label">From:</span>
                        <span class="value">
                            <span
                                class="mb-0">₫</span><?php echo number_format((float) ($price_tour ? $price_tour : 100)); ?>
                        </span>
                        <?php
                        $is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
                        $is_private_only = empty($price_group) && !empty($price_private);
                        if (!$is_private_only && !$is_car_tour):
                            ?>
                            <span class="unit">/ person</span>
                        <?php endif; ?>
                    </div>

                    <a href="#form-booking-tour" class="vm-button d-flex align-items-center justify-content-center"
                        aria-label="button Check Availability">
                        Check Availability
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="#000000">
                            <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>