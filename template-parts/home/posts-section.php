<?php
$heading = get_field('hd_posts_hp');
$sub_heading = get_field('sub_hd_posts_hp');

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
);
$query = new WP_Query($args);

?>
<?php if ($query->have_posts()): ?>
    <section class="vm-section posts-section">
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

            <div class="posts-section__carousel swiper posts-swiper">
                <div class="posts-section__list swiper-wrapper">
                    <?php
                    $post_count = 0;
                    while ($query->have_posts()):
                        $query->the_post();
                        $post_count++;

                        $is_featured = ($post_count === 1);
                        $thumbnail_url = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url() : 'https://placehold.co/600x400';
                        $author_name = get_the_author();
                        $post_date_d = get_the_date('d');
                        $post_date_M = get_the_date('F');
                        ?>

                        <a href="<?= get_permalink(); ?>"
                            class="post-card <?= $is_featured ? 'post-card--featured' : 'post-card--compact' ?> swiper-slide"
                            aria-label="read more <?= the_title(); ?>">
                            <?php if ($is_featured): ?>
                                <div class="post-card__bg" style="background-image: url('<?= $thumbnail_url ?>');"></div>
                                <div class="post-card__overlay"></div>
                                <div class="post-card__date-badge">
                                    <span class="day"><?= $post_date_d ?></span>
                                    <span class="month"><?= $post_date_M ?></span>
                                </div>
                                <div class="post-card__content">
                                    <p class="post-card__author">By <?= $author_name ?></p>
                                    <h3 class="post-card__title h4"><?= the_title(); ?></h3>
                                </div>
                            <?php else: ?>
                                <div class="post-card__thumb">
                                    <img src="<?= $thumbnail_url ?>" alt="image for <?= the_title(); ?>">
                                </div>

                                <div class="post-card__content">
                                    <div class="warp">
                                        <p class="post-card__author">By <?= $author_name ?></p>
                                        <h3 class="post-card__title h6"><?= the_title(); ?></h3>

                                        <div class="post-card__excerpt">
                                            <?= get_the_excerpt() ?>
                                        </div>

                                        <div class="post-card__date-badge">
                                            <span class="day"><?= $post_date_d ?></span>
                                            <span class="month"><?= $post_date_M ?></span>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </a>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>

                <div class="swiper-action d-flex justify-content-center align-items-center d-lg-none">
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

            <div class="posts-section__cta d-flex justify-content-center">
                <?php vm_load_button('/phu-quoc-island-travel-guide/', 'View All', '', 'view all posts') ?>
            </div>
        </div>
    </section>
<?php endif; ?>