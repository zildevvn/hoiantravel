<?php
$heading = get_field('heading_cars_home');
$badge = get_field('badge_cars_home');
$desc = get_field('desc_cars_home');
$services = get_field('list_services');
$currency = get_field('currency', 'option');

$args = array(
    'post_type' => 'cars',
    'posts_per_page' => 5,
    'post_status' => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="ha-section cars-section" id="transfers">
        <div class="container">
            <!-- Intro Header with Right Trust Badges -->
            <div class="cars-section__header">
                <div class="cars-section__header-content">
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
                        <p class="cars-section__desc">
                            <?= $desc ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($services)): ?>
                    <div class="cars-section__trust-list">
                        <?php foreach ($services as $service): ?>
                            <span class="cars-section__trust-item">
                                <?= ha_svg_icon('check') ?>
                                <?= $service['service'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <!-- 4 Route Cards Grid -->
            <div class="cars-section__grid">
                <?php while ($query->have_posts()):
                    $query->the_post();
                    $location = get_field('location_car');
                    $time = get_field('time_car');
                    $price = get_field('price_car');
                    $pick_up = get_field('pick_up_car');
                    $route_display = !empty($location) ? preg_replace('/\s*[-–—]\s*/u', ' ➔ ', $location) : '';
                    ?>
                    <a href="<?= the_permalink(); ?>" class="car-card" aria-label="read more <?php the_title() ?>">

                        <div>
                            <div class="car-card__media">
                                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="image for <?= the_title(); ?>" width="380"
                                    height="260" />

                                <div class="car-card__duration">
                                    <?= $time; ?>
                                </div>
                            </div>

                            <?php if (!empty($route_display)): ?>
                                <div class="car-card__route-wrap">
                                    <span class="car-card__route"><?= esc_html($route_display); ?></span>
                                </div>
                            <?php endif; ?>

                            <h3 class="car-card__title">
                                <?php the_title() ?>
                            </h3>

                            <div class="car-card__specs">
                                <div class="car-card__spec-item" title="Modern Fleet (Sedan, SUV &amp; Van)">
                                    <?= ha_svg_icon('car'); ?>
                                    <span>Modern Fleet (Sedan, SUV &amp; Van)</span>
                                </div>
                                <div class="car-card__spec-item"
                                    title="<?= !empty($pick_up) ? esc_attr($pick_up) : 'Door-to-Door Service'; ?>">
                                    <?= ha_svg_icon('door'); ?>
                                    <span><?= !empty($pick_up) ? esc_html($pick_up) : 'Door-to-Door Service'; ?></span>
                                </div>
                                <div class="car-card__spec-item" title="English-Speaking Driver">
                                    <?= ha_svg_icon('guide'); ?>
                                    <span>English-Speaking Driver</span>
                                </div>
                                <div class="car-card__spec-item" title="Free Cold Water &amp; Wi-Fi">
                                    <?= ha_svg_icon('wifi'); ?>
                                    <span>Free Cold Water &amp; Wi-Fi</span>
                                </div>
                            </div>
                        </div>

                        <div class="car-card__footer">
                            <div class="car-card__price-box">
                                <span class="car-card__price-label">From</span>
                                <span class="car-card__price-val">
                                    <?= esc_html(ha_format_price($price, $currency)); ?>
                                </span>
                            </div>
                            <button class="ha-button" type="button">

                                Book Car
                                <?= ha_svg_icon('arrow-right'); ?>
                            </button>
                        </div>
                    </a>
                <?php endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <!-- Bottom Action Bar & Concierge Note -->
            <div class="cars-section__action">
                <a class="ha-button" href="<?= home_url('/hoi-an-car-rental/') ?>">
                    View All
                    <?= ha_svg_icon('arrow-right'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>