<?php
$heading = get_field('heading_posts_home');
$badge = get_field('badge_posts_home');
$sub_heading = get_field('sub_heading_posts_home');

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 4,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
);
$query = new WP_Query($args);

?>
<?php if ($query->have_posts()): ?>
    <section class="ha-section posts-section" id="section-travel-guide">
        <div class="container">
            <!-- Section Header -->
            <div class="posts-section__header">
                <div class="posts-section__header-content">
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

                    <?php if (!empty($sub_heading)): ?>
                        <p class="posts-section__desc">
                            <?= $sub_heading ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="posts-section__header-action">
                    <a class="ha-button" href="<?php echo home_url('/hoi-an-travel-guide/') ?>">
                        View All
                        <?php echo ha_svg_icon('arrow_next'); ?>
                    </a>
                </div>
            </div>
            <!-- 12-Column Magazine Grid -->
            <div class="posts-section__grid">
                <?php
                $posts = $query->posts;
                $featured_post = isset($posts[0]) ? $posts[0] : null;
                $compact_posts = array_slice($posts, 1);
                ?>

                <?php if ($featured_post):
                    $post = $featured_post;
                    setup_postdata($post);

                    $categories = get_the_category();
                    $category_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : '';
                    $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : 'https://placehold.co/800x500?text=Hoi+An+Travel';
                    $post_date = get_the_date('F Y');
                    $meta_text = strtoupper($post_date) . (!empty($category_name) ? ' • ' . strtoupper($category_name) : '');
                    $word_count = str_word_count(strip_tags(get_the_content()));
                    $reading_minutes = max(1, (int) ceil($word_count / 200));
                    $modified_year = get_the_modified_date('Y');
                    $reading_time = sprintf(__('%d Min Read • Updated for %s', 'hoiantravel'), $reading_minutes, $modified_year);
                    $post_tags = get_the_tags();
                    $location_label = (!empty($post_tags) && !is_wp_error($post_tags)) ? $post_tags[0]->name : 'Hoi An • Vietnam';
                    ?>
                    <!-- Left 7 Columns: Grand Featured Article Card -->
                    <article class="featured-post-card">
                        <div class="featured-post-card__media">
                            <img alt="<?php the_title_attribute(); ?>" class="featured-post-card__img"
                                src="<?= esc_url($thumbnail_url); ?>">
                            <div class="featured-post-card__overlay"></div>
                            <?php if (!empty($category_name)): ?>
                                <span class="featured-post-card__badge">
                                    <?= esc_html($category_name); ?>
                                </span>
                            <?php endif; ?>
                            <span class="featured-post-card__reading-time">
                                <?= esc_html($reading_time); ?>
                            </span>
                        </div>
                        <div class="featured-post-card__body">
                            <div>
                                <div class="featured-post-card__meta">
                                    <?= esc_html($meta_text); ?>
                                </div>
                                <h3 class="featured-post-card__title">
                                    <a href="<?php the_permalink(); ?>" class="text-reset text-decoration-none">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="featured-post-card__excerpt">
                                    <?= esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
                                </p>
                            </div>
                            <div class="featured-post-card__footer">
                                <a class="featured-post-card__link" href="<?php the_permalink(); ?>">
                                    Read Full Guide →
                                </a>
                                <span class="featured-post-card__location"><?= esc_html($location_label); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>

                <?php if (!empty($compact_posts)): ?>
                    <!-- Right 5 Columns: Stacked Compact Article Cards -->
                    <div class="posts-section__list">
                        <?php
                        $tag_modifiers = array('primary', 'secondary', 'accent');
                        foreach ($compact_posts as $idx => $compact_post):
                            $post = $compact_post;
                            setup_postdata($post);

                            $categories = get_the_category();
                            $category_name = (!empty($categories) && !is_wp_error($categories)) ? $categories[0]->name : __('Guide', 'hoiantravel');
                            $word_count = str_word_count(strip_tags(get_the_content()));
                            $reading_minutes = max(1, (int) ceil($word_count / 200));
                            $tag_text = sprintf('%s • %d MIN READ', strtoupper($category_name), $reading_minutes);
                            $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'medium') : 'https://placehold.co/300x300?text=Hoi+An+Travel';
                            $tag_modifier = $tag_modifiers[$idx % 3];
                            ?>
                            <a href="<?php the_permalink(); ?>" class="compact-post-card">
                                <div class="compact-post-card__media">
                                    <img alt="<?php the_title_attribute(); ?>" class="compact-post-card__img"
                                        src="<?= esc_url($thumbnail_url); ?>">
                                </div>
                                <div class="compact-post-card__content">
                                    <span class="compact-post-card__tag compact-post-card__tag--<?= esc_attr($tag_modifier); ?>">
                                        <?= esc_html($tag_text); ?>
                                    </span>
                                    <h4 class="compact-post-card__title">
                                        <?php the_title(); ?>
                                    </h4>
                                </div>
                                <div class="compact-post-card__arrow">
                                    ➔
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php wp_reset_postdata(); ?>
            </div>
            <!-- Mobile CTA Button -->
            <div class="posts-section__mobile-action">
                <a class="ha-button" href="<?php echo home_url('/hoi-an-travel-guide/') ?>">
                    View All
                    <?php echo ha_svg_icon('arrow_next'); ?>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>