<?php
/**
 * Single Post Main Section - Article Content & Sidebar
 *
 * @package hoiantravel
 */

$post_id = get_the_ID();
$form = get_field('form_support_blog', 'option');

// Author details
$author_id = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id) ?: __('Hoi An Insider', 'hoiantravel');
$author_desc = get_the_author_meta('description', $author_id);
$author_link = get_author_posts_url($author_id);
$author_avatar = get_avatar($author_id, 72, '', esc_attr($author_name), ['class' => 'author-box__avatar-img']);

// Post tags
$post_tags = get_the_tags($post_id);
?>

<section class="ha-section main-section single-post-main">
    <div class="container">
        <div class="main-section-inner d-flex justify-content-between">
            <!-- Article Body Column -->
            <article class="main-section-left single-post-article">
                <!-- Dynamic Table of Contents (Generated automatically if headings exist) -->
                <div id="vm-table-of-content"></div>

                <!-- Rich Long-form Prose Content -->
                <div class="main-section-left__content single-post-content single-post-prose entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Article Footer: Tags (Only rendered if tags exist) -->
                <?php if (!empty($post_tags) && !is_wp_error($post_tags)): ?>
                    <footer class="single-post-footer">
                        <div class="single-post-tags d-flex flex-wrap align-items-center gap-2">
                            <span class="single-post-tags__label d-inline-flex align-items-center gap-1">
                                <?= ha_svg_icon('tag'); ?>
                                <span><?= esc_html__('Tags:', 'hoiantravel'); ?></span>
                            </span>
                            <div class="single-post-tags__list d-flex flex-wrap gap-2">
                                <?php foreach ($post_tags as $tag_item): ?>
                                    <a href="<?= esc_url(get_tag_link($tag_item->term_id)); ?>" class="single-post-tag-item">
                                        #<?= esc_html($tag_item->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </footer>
                <?php endif; ?>

                <!-- Author Bio Box -->
                <div class="single-post-author-box d-flex align-items-start gap-4">
                    <div class="author-box__avatar-wrap">
                        <?= $author_avatar; ?>
                    </div>
                    <div class="author-box__content">
                        <div class="author-box__eyebrow"><?= esc_html__('Article Contributor', 'hoiantravel'); ?></div>
                        <h4 class="author-box__name">
                            <a href="<?= esc_url($author_link); ?>"><?= esc_html($author_name); ?></a>
                        </h4>
                        <p class="author-box__bio mb-0">
                            <?php
                            if (!empty($author_desc)) {
                                echo esc_html($author_desc);
                            } else {
                                esc_html_e('Local travel specialist and storyteller dedicated to helping travelers uncover authentic cultural heritage, lantern-lit streets, and hidden wonders across Hoi An and Central Vietnam.', 'hoiantravel');
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="single-post-comments-wrap">
                    <?php comments_template(); ?>
                </div>
            </article>

            <!-- Sticky Sidebar Column -->
            <aside class="main-section-right sidebar single-post-sidebar">
                <!-- Search Widget -->
                <div class="sidebar-widget widget-search">
                    <h3 class="widget-title"><?= esc_html__('Search Articles', 'hoiantravel'); ?></h3>
                    <form role="search" method="get" class="search-form" action="<?= esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <input type="search" class="search-field form-control"
                                placeholder="<?= esc_attr__('Search travel guides...', 'hoiantravel'); ?>"
                                value="<?= esc_attr(get_search_query()); ?>" name="s"
                                title="<?= esc_attr__('Search for:', 'hoiantravel'); ?>" />
                            <button type="submit" class="search-submit"
                                aria-label="<?= esc_attr__('Submit search', 'hoiantravel'); ?>">
                                <?= ha_svg_icon('search-icon'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Recent Posts Widget -->
                <div class="sidebar-widget widget-recent-posts">
                    <h3 class="widget-title"><?= esc_html__('Recent Stories', 'hoiantravel'); ?></h3>
                    <div class="recent-posts-list">
                        <?php
                        $recent_args = [
                            'post_type' => 'post',
                            'posts_per_page' => 4,
                            'post_status' => 'publish',
                            'post__not_in' => [$post_id],
                            'ignore_sticky_posts' => true,
                        ];
                        $recent_query = new WP_Query($recent_args);

                        if ($recent_query->have_posts()):
                            while ($recent_query->have_posts()):
                                $recent_query->the_post();
                                $recent_thumb = has_post_thumbnail()
                                    ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
                                    : '';
                                ?>
                                <div class="recent-post-item d-flex align-items-center gap-3">
                                    <div class="recent-post-item__thumb">
                                        <a href="<?php the_permalink(); ?>" aria-label="<?= esc_attr(get_the_title()); ?>">
                                            <?php if (!empty($recent_thumb)): ?>
                                                <img src="<?= esc_url($recent_thumb); ?>" alt="<?= esc_attr(get_the_title()); ?>"
                                                    loading="lazy" />
                                            <?php else: ?>
                                                <div class="placeholder-thumb">
                                                    <?= ha_svg_icon('image'); ?>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="recent-post-item__info">
                                        <h4 class="recent-post-item__title">
                                            <a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
                                        </h4>
                                        <div class="recent-post-item__date d-flex align-items-center gap-1">
                                            <?= ha_svg_icon('calendar'); ?>
                                            <span><?php echo esc_html(get_the_date('M j, Y')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else:
                            ?>
                            <p class="text-muted small mb-0"><?= esc_html__('No recent posts found.', 'hoiantravel'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Support / Quote Form Widget -->
                <?php if (!empty($form)): ?>
                    <div class="sidebar-widget widget-form-support">
                        <h3 class="widget-title"><?= esc_html__('Request A Free Quote', 'hoiantravel'); ?></h3>
                        <?= do_shortcode($form); ?>
                    </div>
                <?php endif; ?>

                <!-- Editorial Note / About Guide Widget -->
                <div class="sidebar-widget widget-about-guide">
                    <div class="widget-about-guide__badge d-inline-flex align-items-center gap-1">
                        <?= ha_svg_icon('shield-check'); ?>
                        <span><?= esc_html__('Authentic Experience', 'hoiantravel'); ?></span>
                    </div>
                    <h3 class="widget-title"><?= esc_html__('Hoi An Travel Guide', 'hoiantravel'); ?></h3>
                    <p class="widget-about-guide__text">
                        <?= esc_html__('Curated by local destination specialists. We share insider secrets, heritage walking itineraries, and authentic culinary journeys in Hoi An.', 'hoiantravel'); ?>
                    </p>
                    <a href="<?= esc_url(home_url('/hoi-an-tours/')); ?>"
                        class="widget-about-guide__cta d-inline-flex align-items-center gap-2">
                        <span><?= esc_html__('Explore Handcrafted Tours', 'hoiantravel'); ?></span>
                        <?= ha_svg_icon('arrow-right'); ?>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>