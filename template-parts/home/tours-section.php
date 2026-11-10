<?php
$heading = get_field('heading_tour_home');
$badge = get_field('badge_tour_home');
$desc = get_field('desc_tours_home');
$currency = get_field('currency', 'option');
// ₫ : VND
// $ : USD
// € : EUR

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 5,
    'post_status' => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="ha-section tours-section" id="section-tours">
        <div class="container">
            <div class="tours-section__header">
                <div class="tours-section__header-content">

                    <?php if (!empty($badge)): ?>
                        <div class="ha-badge">
                            <?php echo ha_svg_icon('badge'); ?>
                            <?= $badge ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="mb-0">
                            <?= $heading ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($desc)): ?>
                        <p class="tours-section__desc">
                            <?= $desc ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="tours-section__nav">
                    <span class="tours-section__nav-counter tour-swiper-counter">01 / 04</span>
                    <div class="tours-section__nav-buttons">
                        <button class="swiper-prev tour-swiper-prev" type="button" aria-label="Previous tours">
                            <?php echo ha_svg_icon('arrow_prev'); ?>
                        </button>
                        <button class="swiper-next tour-swiper-next" type="button" aria-label="Next tours">
                            <?php echo ha_svg_icon('arrow_next'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Tours Carousel Cards -->
            <div class="swiper tour-swiper overflow-visible">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()):
                        $query->the_post();

                        ?>
                        <div class="swiper-slide">
                            <?php ha_item_tour(); ?>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <!-- Centered View All Tours Button -->
            <div class="tours-section__action">
                <a class="ha-button" href="<?php echo home_url('/hoi-an-tours/') ?>">
                    View All Tours
                    <?php echo ha_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>