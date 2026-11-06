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
                        $tour_cats = get_the_terms(get_the_ID(), 'tour_cats');
                        $cat_name = (!empty($tour_cats) && !is_wp_error($tour_cats)) ? implode(', ', wp_list_pluck($tour_cats, 'name')) : '';

                        $tour_locs = get_the_terms(get_the_ID(), 'tour_locations');
                        $loc_name = (!empty($tour_locs) && !is_wp_error($tour_locs)) ? implode(', ', wp_list_pluck($tour_locs, 'name')) : '';
                        $price = get_field('price_tour');
                        ?>
                        <div class="swiper-slide">
                            <a href="<?= the_permalink(); ?>" class="tour-card" aria-label="read more <?php the_title() ?>">

                                <div class="tour-card__media">
                                    <img src="<?= get_the_post_thumbnail_url(); ?>" alt="image for <?= the_title(); ?>"
                                        width="380" height="260" />

                                    <?php if (!empty($cat_name)): ?>
                                        <div class="tour-card__duration"><?= esc_html($cat_name); ?></div>
                                    <?php endif; ?>
                                </div>

                                <?php
                                // Rating data: same post meta source as kk-star-ratings (count_default / ratings_default),
                                // falling back to the legacy keys overridden in inc/hooks.php.
                                $kk_count = (int) get_post_meta(get_the_ID(), '_kksr_count_default', true);
                                $kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings_default', true);
                                if ($kk_count <= 0) {
                                    $kk_count = (int) get_post_meta(get_the_ID(), '_kksr_casts', true);
                                    $kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings', true);
                                }
                                $kk_score = $kk_count > 0 ? round(min($kk_ratings / $kk_count, 5), 1, PHP_ROUND_HALF_DOWN) : 0;
                                ?>
                                <div class="tour-card__body">
                                    <div>
                                        <div class="tour-card__meta">
                                            <span class="tour-card__location">
                                                <?php if (!empty($loc_name)): ?>
                                                    <?php echo ha_svg_icon('location'); ?>
                                                    <span class="tour-card__location-text"><?= esc_html($loc_name); ?></span>
                                                <?php endif; ?>
                                            </span>
                                            <?php if ($kk_count > 0): ?>
                                                <span class="tour-card__rating">
                                                    <span class="tour-card__rating-star" aria-hidden="true">★</span>
                                                    <span class="tour-card__rating-score"><?= esc_html($kk_score); ?>/5</span>
                                                    <span
                                                        class="tour-card__rating-count">(<?= esc_html(number_format_i18n($kk_count)); ?>)</span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="tour-card__title h5">
                                            <?php the_title() ?>
                                        </h3>
                                        <div class="tour-card__highlights">
                                            <span class="tour-card__highlight-item">
                                                <?php echo ha_svg_icon('guide'); ?>
                                                <span>English Guide</span>
                                            </span>
                                            <span class="tour-card__highlight-item">
                                                <?php echo ha_svg_icon('pickup'); ?>
                                                <span>Pickup Incl.</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="tour-card__footer">
                                        <div class="tour-card__price-box">
                                            <span class="tour-card__price-label">From</span>
                                            <span class="tour-card__price-val"><?= esc_html(ha_format_price($price, $currency)); ?></span>
                                        </div>
                                        <button class="ha-button" type="button">
                                            Book Tour
                                            <?php echo ha_svg_icon('arrow_next'); ?>
                                        </button>
                                    </div>
                                </div>
                            </a>
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