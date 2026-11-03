<?php
$heading = get_field('hd_ab_blog_tpl');
$sub_heading = get_field('sub_hd_ab_blog_tpl');
$desc = get_field('desc_ab_blog_tpl');
$image_ab = get_field('img_ab_blog_tpl');
?>
<section class="ha-section about-section">
    <div class="about-section__graphic">
        <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
            alt="image graphic for Phu Quoc Island Travel Guide" />
    </div>

    <div class="container">
        <div class="about-section-warp">
            <div class="about-section__header">
                <div class="header-content">
                    <?php if (!empty($sub_heading)): ?>
                        <p class="sub-heading h6">
                            <?= $sub_heading ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($heading)): ?>
                        <h2 class="h4">
                            <?= $heading ?>
                        </h2>
                    <?php endif; ?>

                    <?= $desc ?>
                </div>
            </div>

            <div class="about-section__posts">
                <?php
                $recent_posts = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish'
                ]);

                if ($recent_posts->have_posts()):
                    while ($recent_posts->have_posts()):
                        $recent_posts->the_post();
                        ha_post_item();
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </div>
</section>