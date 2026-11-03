<?php
$heading = get_field('hd_pg_tours_tpl_ctt');
$sub_heading = get_field('sub_hd_pg_tours_tpl_ctt');

$args = array(
    'post_type' => 'tours',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'tour_cats',
            'field' => 'slug',
            'terms' => 'package-tours',
        ),
    ),
);
$query = new WP_Query($args);
?>
<?php if ($query->have_posts()): ?>
    <section class="vm-section package-tours-section">
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

            <div class="package-tours-section__carousel swiper tours-carousel">
                <div class=" swiper-wrapper">
                    <?php while ($query->have_posts()):
                        $query->the_post();
                        vm_item_tour('swiper-slide');
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>


                <div class="swiper-action d-flex justify-content-center align-items-center">
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="#000000">
                            <path d="M21 12L3 12M3 12L11.5 3.5M3 12L11.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="#000000">
                            <path d="M3 12L21 12M21 12L12.5 3.5M21 12L12.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="package-tours-section__actions d-flex align-items-center justify-content-center">
                <?php vm_load_button('/phu-quoc-tours', 'View All', '', 'View All package Tours') ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php wp_reset_postdata(); ?>