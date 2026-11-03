<?php
$heading = get_field('heading_car_tours');
$sub_heading = get_field('sub_hd_car_tours');

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 4,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'tour_cats',
            'field' => 'slug',
            'terms' => 'car-tours',
        ),
    ),
);

$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="vm-section car-tour-section">
        <div class="car-tour-section__graphic">
            <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-003.png"
                alt="image graphic phu quoc island tour" width="300" height="260" />
        </div>
        <div class="container">
            <?php vm_icon_heading() ?>
            <?php if (!empty($heading)): ?>
                <h2 class="vm-heading">
                    <?= $heading ?>
                </h2>
            <?php endif; ?>
            <?php if (!empty($sub_heading)): ?>
                <p class="vm-sub-heading">
                    <?= $sub_heading ?>
                </p>
            <?php endif; ?>

            <div class="car-tour-section__carousel swiper car-tours-swiper">
                <div class="car-tour-section__grid swiper-wrapper">
                    <?php while ($query->have_posts()):
                        $query->the_post();
                        $pick_up = get_field('pick_up_car');
                        $drop_off = get_field('drop_off_car');
                        $original_price = get_field('original_price_car');
                        $discount_price = get_field('discount_price_car');
                        $group_size = get_field('group_size_car');
                        $duration = get_field('duration_car');
                        ?>
                        <a href="<?= the_permalink(); ?>" class="car-tour-item d-flex swiper-slide"
                            aria-label="read more <?php the_title() ?>">
                            <div class="car-tour-item__thumb">
                                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="image for <?= the_title(); ?>" width="257"
                                    height="218" />
                            </div>

                            <div class="car-tour-item-content">
                                <div class="car-tour-item-content-wrap">
                                    <div class="car-tour-item__locations d-flex">
                                        <?php if (!empty($pick_up)): ?>
                                            <span class="pick-up d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="car-tour-icon">
                                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                                    <path d="M12 14V6" />
                                                    <path d="M9 9l3-3 3 3" />
                                                </svg>
                                                <?= $pick_up ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if (!empty($drop_off)): ?>
                                            <span class="drop-off d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="car-tour-icon">
                                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                                    <path d="M12 6v8" />
                                                    <path d="M9 11l3 3 3-3" />
                                                </svg>
                                                <?= $drop_off ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="car-tour-item__title h5">
                                        <?php the_title(); ?>
                                    </h3>

                                    <div class="car-tour-item__info d-flex">
                                        <?php if (!empty($duration)): ?>
                                            <span class="time d-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="lucide lucide-clock-check-icon lucide-clock-check">
                                                    <path d="M12 6v6l4 2"></path>
                                                    <path d="M22 12a10 10 0 1 0-11 9.95"></path>
                                                    <path d="m22 16-5.5 5.5L14 19"></path>
                                                </svg>
                                                <?= $duration ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if (!empty($group_size)): ?>
                                            <span class="group-size d-flex">
                                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" color="#000000">
                                                    <path d="M7 18V17C7 14.2386 9.23858 12 12 12V12C14.7614 12 17 14.2386 17 17V18"
                                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                    <path d="M1 18V17C1 15.3431 2.34315 14 4 14V14" stroke="#000000"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M23 18V17C23 15.3431 21.6569 14 20 14V14" stroke="#000000"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path
                                                        d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z"
                                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                    <path
                                                        d="M4 14C5.10457 14 6 13.1046 6 12C6 10.8954 5.10457 10 4 10C2.89543 10 2 10.8954 2 12C2 13.1046 2.89543 14 4 14Z"
                                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                    <path
                                                        d="M20 14C21.1046 14 22 13.1046 22 12C22 10.8954 21.1046 10 20 10C18.8954 10 18 10.8954 18 12C18 13.1046 18.8954 14 20 14Z"
                                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                    </path>
                                                </svg>
                                                <?= $group_size ?> guests
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="car-tour-item-meta">
                                        <div class="vm-rating d-flex align-items-center">
                                            <?php if (function_exists('kk_star_ratings')): ?>
                                                <?php echo kk_star_ratings(); ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($original_price): ?>
                                            <h4 class="car-tour-item__price h6 mb-0">
                                                From <span class="h5 mb-0"><?= number_format($original_price, 0, '.', ',') ?>
                                                    <span class="h5 mb-0">₫</span>
                                                </span> </h4>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <div class=" swiper-action d-flex justify-content-center align-items-center d-lg-none mt-4">
                    <div class="swiper-button-prev">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="#000000">
                            <path d="M21 12L3 12M3 12L11.5 3.5M3 12L11.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </div>
                    <div class="swiper-button-next">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="#000000">
                            <path d="M3 12L21 12M21 12L12.5 3.5M21 12L12.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="car-tour-section__actions d-flex align-items-center justify-content-center">
                <?php vm_load_button('/car-rental-in-phu-quoc/', 'View All', '', 'View All car rental Tours') ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>