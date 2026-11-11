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
                    ha_item_car();
                endwhile;
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