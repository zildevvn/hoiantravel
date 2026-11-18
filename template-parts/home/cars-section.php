<?php
$heading  = get_field('heading_cars_home');
$badge    = get_field('badge_cars_home');
$desc     = get_field('desc_cars_home');
$services = get_field('list_services');
$currency = get_field('currency', 'option');

$args = array(
    'post_type'      => 'cars',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <?php
    $car_count = (int) $query->post_count;
    ?>
    <section class="ha-section cars-section" id="transfers" aria-label="<?php esc_attr_e('Private Car Transfers', 'hoiantravel'); ?>">
        <div class="container">
            <!-- Intro Header with Right Trust Badges -->
            <div class="cars-section__header">
                <div class="cars-section__header-content">
                    <?php if (!empty($badge)): ?>
                        <div class="ha-badge">
                            <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('car-simple'); ?></span>
                            <span class="ha-badge__text"><?= esc_html($badge) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="cars-section__title ha-heading mb-0">
                            <?= esc_html($heading) ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($desc)): ?>
                        <p class="cars-section__desc">
                            <?= esc_html($desc) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($services) && is_array($services)): ?>
                    <div class="cars-section__trust-list">
                        <?php foreach ($services as $service): ?>
                            <?php if (!empty($service['service'])): ?>
                                <span class="cars-section__trust-item">
                                    <span class="cars-section__trust-icon" aria-hidden="true"><?= ha_svg_icon('check') ?></span>
                                    <span><?= esc_html($service['service']) ?></span>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Adaptive Route Cards Grid (Preserves all 5 items in balanced Bento layout) -->
            <div class="cars-section__grid cars-section__grid--count-<?= esc_attr($car_count) ?>">
                <?php while ($query->have_posts()):
                    $query->the_post();
                    ha_item_car();
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <!-- Bottom Action Bar & Concierge Note -->
            <div class="cars-section__action">
                <a class="ha-button" href="<?= esc_url(home_url('/hoi-an-car-rental/')) ?>">
                    <?php esc_html_e('View All', 'hoiantravel'); ?>
                    <?= ha_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
