<?php
$heading  = get_field('heading_tour_home');
$badge    = get_field('badge_tour_home');
$desc     = get_field('desc_tours_home');
$currency = get_field('currency', 'option');
// ₫ : VND
// $ : USD
// € : EUR

$args = array(
    'post_type'      => 'tours',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <?php
    $total_tours = (int) $query->post_count;
    ?>
    <section class="ha-section tours-section" id="section-tours" aria-label="<?php esc_attr_e('Curated Hoi An Tours', 'hoiantravel'); ?>">
        <div class="container">
            <div class="tours-section__header">
                <div class="tours-section__header-content">

                    <?php if (!empty($badge)): ?>
                        <div class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('feature-compass'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($badge) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="tours-section__title ha-heading mb-0">
                            <?= esc_html($heading) ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($desc)): ?>
                        <p class="tours-section__desc">
                            <?= esc_html($desc) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="tours-section__nav">
                    <span class="tours-section__nav-counter tour-swiper-counter"><?= sprintf('01 / %02d', $total_tours) ?></span>
                    <div class="tours-section__nav-buttons">
                        <button class="swiper-prev tour-swiper-prev" type="button" aria-label="<?php esc_attr_e('Previous tours', 'hoiantravel'); ?>">
                            <?php echo ha_svg_icon('arrow_prev'); ?>
                        </button>
                        <button class="swiper-next tour-swiper-next" type="button" aria-label="<?php esc_attr_e('Next tours', 'hoiantravel'); ?>">
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
                <a class="ha-button" href="<?php echo esc_url(home_url('/hoi-an-tours/')) ?>">
                    <?php esc_html_e('View All Tours', 'hoiantravel'); ?>
                    <?php echo ha_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
