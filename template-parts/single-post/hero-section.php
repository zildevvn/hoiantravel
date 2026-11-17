<?php
/**
 * Single Post Hero Section
 *
 * Fully synchronized with Hoi An Travel Shared Hero System (Single Tour & Single Car)
 * Features: Indochine atmospheric lighting, background featured image, refined breadcrumbs pill,
 * taxonomy category badge, post title, excerpt lead, and metadata chips (author, date, reading time, comments).
 *
 * @package hoiantravel
 */

$post_id   = get_the_ID();
$title     = get_the_title($post_id);
$image     = get_the_post_thumbnail_url($post_id, 'full');
$image_id  = get_post_thumbnail_id($post_id);
$alt       = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
if (empty($alt)) {
    $alt = $title;
}
$has_image = !empty($image);

// Categories
$categories = get_the_category($post_id);
$category   = (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : null;

// Author
$author_id   = get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id) ?: __('Hoi An Insider', 'hoiantravel');
$author_link = get_author_posts_url($author_id);

// Date
$date_formatted = get_the_date('M j, Y', $post_id);

// Estimated reading time (~200 words per minute)
$content_raw  = get_post_field('post_content', $post_id);
$word_count   = str_word_count(wp_strip_all_tags($content_raw));
$reading_time = max(1, (int) ceil($word_count / 200));
?>

<section class="ha-section hero-section-shared hero-section-shared--single-tour hero-section-shared--single-post<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
    <!-- Background Image & Atmospheric Indochine Layers -->
    <div class="ha-section__bg hero-section-shared__bg">
        <?php if ($has_image): ?>
            <img src="<?= esc_url($image); ?>" alt="<?= esc_attr($alt); ?>" loading="eager" />
        <?php else: ?>
            <div class="hero-section-shared__fallback-pattern" aria-hidden="true">
                <?= ha_svg_icon('about-watermark'); ?>
            </div>
        <?php endif; ?>
        <div class="hero-section-shared__overlay"></div>
        <div class="hero-section-shared__glow"></div>
    </div>

    <!-- Main Content Container -->
    <div class="container hero-section-shared__container">
        <div class="hero-section-shared__box">

            <!-- Breadcrumbs -->
            <div class="hero-section-shared__breadcrumbs-wrap">
                <?php ha_breadcrumbs($title); ?>
            </div>

            <!-- Post Category Badge -->
            <?php if ($category): ?>
                <div class="hero-section-shared__badge-wrap">
                    <a href="<?= esc_url(get_category_link($category->term_id)); ?>" class="ha-badge hero-tour-badge hero-post-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <span><?= esc_html($category->name); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Main Title -->
            <h1 class="ha-heading hero-section-shared__title">
                <?= esc_html($title); ?>
            </h1>

            <!-- Excerpt / Short Description -->
            <?php if (has_excerpt($post_id)): ?>
                <div class="hero-section-shared__desc hero-tour-desc hero-post-desc">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <!-- Post Metadata Chips (Author, Date, Reading Time, Comments) -->
            <div class="hero-tour-meta hero-post-meta" role="list">
                <div class="hero-tour-meta__item" role="listitem">
                    <span class="hero-tour-meta__icon" aria-hidden="true">
                        <?= ha_svg_icon('user'); ?>
                    </span>
                    <span class="hero-tour-meta__label">
                        <a href="<?= esc_url($author_link); ?>" class="hero-post-meta__link">
                            <?= esc_html($author_name); ?>
                        </a>
                    </span>
                </div>

                <div class="hero-tour-meta__item" role="listitem">
                    <span class="hero-tour-meta__icon" aria-hidden="true">
                        <?= ha_svg_icon('calendar'); ?>
                    </span>
                    <span class="hero-tour-meta__label"><?= esc_html($date_formatted); ?></span>
                </div>

                <div class="hero-tour-meta__item" role="listitem">
                    <span class="hero-tour-meta__icon" aria-hidden="true">
                        <?= ha_svg_icon('clock'); ?>
                    </span>
                    <span class="hero-tour-meta__label"><?= sprintf(esc_html__('%d min read', 'hoiantravel'), $reading_time); ?></span>
                </div>

                <?php if (comments_open($post_id)): ?>
                    <div class="hero-tour-meta__item" role="listitem">
                        <span class="hero-tour-meta__icon" aria-hidden="true">
                            <?= ha_svg_icon('message-square'); ?>
                        </span>
                        <a href="#comments" class="hero-tour-meta__label hero-post-meta__link">
                            <?php
                            $comments_number = get_comments_number($post_id);
                            if ($comments_number == 0) {
                                echo esc_html__('No Comments', 'hoiantravel');
                            } elseif ($comments_number == 1) {
                                echo esc_html__('1 Comment', 'hoiantravel');
                            } else {
                                echo sprintf(esc_html__('%s Comments', 'hoiantravel'), number_format_i18n($comments_number));
                            }
                            ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Decorative Indochine Bottom Ribbon -->
    <div class="hero-section-shared__ribbon" aria-hidden="true">
        <?= ha_svg_icon('indochine-ribbon'); ?>
    </div>
</section>
