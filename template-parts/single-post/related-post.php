<?php

$current_post_id = get_the_ID();

$args = [
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'post__not_in' => [$current_post_id],
    'orderby' => 'rand',
];

$the_query = new WP_Query($args);

?>

<?php if ($the_query->have_posts()): ?>
    <section class="vm-section related-post-section">
        <div class="container">
            <?php vm_icon_heading() ?>

            <h2 class="vm-heading">
                Related <span>articles</span>
            </h2>

            <p class="vm-sub-heading">Explore our Phu Quoc Island Tours to discover the best beaches and attractions.</p>

            <div class="related-post-section__list swiper">
                <div class="swiper-wrapper">
                    <?php
                    while ($the_query->have_posts()):
                        $the_query->the_post();
                        ?>
                        <div class="swiper-slide">
                            <?php vm_post_item(); ?>
                        </div>
                        <?php
                    endwhile;

                    wp_reset_postdata();
                    ?>
                </div>
            </div>

        </div>
    </section>
<?php endif; ?>