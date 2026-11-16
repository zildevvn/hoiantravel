<?php
$image = get_the_post_thumbnail_url();
$form = get_field('form_support_blog', 'option');
?>
<section class="vm-section main-section">
    <div class="container">
        <div class="main-section-inner d-flex justify-content-between">
            <div class="main-section-left">
                <div class="main-section-left__thumb">
                    <img src="<?= $image ?>" alt="image <?= the_title() ?>">
                </div>

                <div class="main-section-left__info post-meta d-flex flex-wrap align-items-center gap-4">
                    <div class="post-meta__item d-flex align-items-center gap-2">
                        <?= ha_svg_icon('user'); ?>
                        <span>By <a
                                href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a></span>
                    </div>

                    <div class="post-meta__item d-flex align-items-center gap-2">
                        <?= ha_svg_icon('calendar'); ?>
                        <span><?php echo esc_html(get_the_date()); ?></span>
                    </div>

                    <?php if (comments_open()): ?>
                        <div class="post-meta__item d-flex align-items-center gap-2">
                            <?= ha_svg_icon('message-square'); ?>
                            <span>
                                <?php
                                $comments_number = get_comments_number();
                                if ($comments_number == 0) {
                                    echo 'No Comments';
                                } elseif ($comments_number == 1) {
                                    echo '1 Comment';
                                } else {
                                    echo sprintf('%s Comments', number_format_i18n($comments_number));
                                }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="main-section-left__content">
                    <?php
                    the_content();
                    ?>
                </div>

                <?php
                comments_template();
                ?>
            </div>
            <div class="main-section-right sidebar">
                <div class="sidebar-widget widget-search">
                    <h3 class="widget-title">Search</h3>
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <input type="search" class="search-field form-control" placeholder="Search..."
                                value="<?php echo get_search_query(); ?>" name="s" title="Search for:" />
                            <button type="submit" class="search-submit" aria-label="Submit search">
                                <?= ha_svg_icon('search-icon'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="sidebar-widget widget-recent-posts">
                    <h3 class="widget-title">Recent Posts</h3>
                    <div class="recent-posts-list">
                        <?php
                        $recent_args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 4,
                            'post_status' => 'publish',
                            'post__not_in' => array(get_the_ID()),
                            'ignore_sticky_posts' => true
                        );
                        $recent_query = new WP_Query($recent_args);

                        if ($recent_query->have_posts()):
                            while ($recent_query->have_posts()):
                                $recent_query->the_post();
                                ?>
                                <div class="recent-post-item d-flex align-items-center gap-3">
                                    <div class="recent-post-item__thumb">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('thumbnail'); ?>
                                            <?php else: ?>
                                                <div class="placeholder-thumb"></div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="recent-post-item__info">
                                        <h4 class="recent-post-item__title">
                                            <a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
                                        </h4>
                                        <div class="recent-post-item__date d-flex align-items-center gap-2">
                                            <?= ha_svg_icon('calendar'); ?>
                                            <span><?php echo esc_html(get_the_date()); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            ?>
                            <p>No recent posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($form)): ?>
                    <div class="sidebar-widget widget-form-support">
                        <h3 class="widget-title">Request A Free Quote</h3>
                        <?php echo do_shortcode($form); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>