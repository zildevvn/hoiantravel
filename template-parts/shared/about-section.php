<?php
/**
 * Shared Component: About Section / HA Media Section
 *
 * Layout refactored to match Blog About Section (4-Image Clover Collage + Story Column)
 * Fully synchronized with Hoi An Travel Design System.
 *
 * Supported arguments in $args:
 * - heading (string): Section title
 * - sub_heading (string): Eyebrow badge or subtitle
 * - description (string): Rich WYSIWYG narrative description
 * - gallery (array): Array of image URLs or image objects
 * - alt_text (string): Descriptive fallback ALT text
 * - append_content (string): Additional markup (e.g. CTA buttons) appended in narrative column
 * - prepend_section (string): Additional decorative markup prepended at the top of section
 * - counters (array): Optional counter metrics
 *
 * @package hoiantravel
 */

$heading         = $args['heading'] ?? '';
$sub_hd          = $args['sub_heading'] ?? '';
$desc            = $args['description'] ?? '';
$gallerys        = $args['gallery'] ?? [];
$alt_text        = $args['alt_text'] ?? '';
$append_content  = $args['append_content'] ?? '';
$prepend_section = $args['prepend_section'] ?? '';
$counters        = $args['counters'] ?? [];
$badge_title     = !empty($args['badge_title']) ? $args['badge_title'] : 'Hoi An Travel';

if (empty($alt_text)) {
    $alt_text = !empty($heading) ? strip_tags($heading) : __('Hoi An Travel Experience', 'hoiantravel');
}

// Normalize all incoming gallery items
$gallery_all = [];
if (!empty($gallerys) && is_array($gallerys)) {
    $gallery_all = array_values($gallerys);
}

// Build 4-item clover collage array from ACF gallery or curated fallbacks (same logic as blog-about-section)
$collage_items = [];
if (!empty($gallery_all)) {
    $collage_items = $gallery_all;
    while (count($collage_items) < 4) {
        $collage_items = array_merge($collage_items, $gallery_all);
    }
    $collage_items = array_slice($collage_items, 0, 4);
} else {
    $fallback_images = [
        '/wp-content/uploads/2026/11/image-hoi-an-0002.jpg',
        '/wp-content/uploads/2026/11/location-My-Son-Sanctuary.webp',
        '/wp-content/uploads/2026/11/locaiton-Hue-Imperial-City-768x512.webp',
        '/wp-content/uploads/2026/11/img-faq-hoi-an-attractions-768x432.webp',
    ];
    foreach ($fallback_images as $fallback_url) {
        $collage_items[] = [
            'url' => home_url($fallback_url),
            'alt' => $alt_text,
        ];
    }
}
?>

<section class="ha-section about-section ha-media-carousel-section blog-about-section">
    <?= $prepend_section; ?>

    <div class="container">
        <!-- Optional Counters Row (if present) -->
        <?php if (!empty($counters) && is_array($counters)): ?>
            <div class="ha-counters ha-media-carousel-section__counters mb-4 mb-lg-5">
                <?php foreach ($counters as $key => $counter): ?>
                    <div class="counter-item">
                        <?php if (!empty($counter['icon'])): ?>
                            <div class="counter-item__icon">
                                <img src="<?= esc_url($counter['icon']); ?>"
                                     alt="icon for <?= esc_attr($counter['label'] ?? ''); ?>"
                                     width="50"
                                     height="50"
                                     loading="lazy" />
                            </div>
                        <?php endif; ?>

                        <div class="counter-item-content">
                            <h3 class="h6 counter-item__label">
                                <?= esc_html($counter['label'] ?? ''); ?>
                            </h3>
                            <p class="ha-counter mb-0 h2">
                                <?= esc_html($counter['number'] ?? ''); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="blog-about-section__wrap ha-media-carousel-section__wrap">
            <!-- Left Column: 4-Image Clover/Petal Grid + Center Badge -->
            <div class="blog-about-section__media ha-media-carousel-section__media">
                <div class="blog-about-collage">
                    <div class="blog-about-collage__grid">
                        <?php foreach ($collage_items as $index => $item):
                            $img_url = is_array($item) ? (!empty($item['sizes']['large']) ? $item['sizes']['large'] : (!empty($item['url']) ? $item['url'] : '')) : (string) $item;
                            $img_alt = is_array($item) ? (!empty($item['alt']) ? $item['alt'] : sprintf('%s %d', $alt_text, $index + 1)) : sprintf('%s %d', $alt_text, $index + 1);
                            ?>
                            <div class="blog-about-collage__item blog-about-collage__item--<?= $index + 1 ?>">
                                <a href="<?= esc_url($img_url); ?>"
                                   class="blog-about-collage__link"
                                   data-fancybox="ha-media-gallery"
                                   data-caption="<?= esc_attr($img_alt); ?>"
                                   aria-label="<?= esc_attr(sprintf(__('View image %d: %s', 'hoiantravel'), $index + 1, $img_alt)); ?>">
                                    <img src="<?= esc_url($img_url); ?>" alt="<?= esc_attr($img_alt); ?>" loading="lazy">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Hidden Fancybox links for all remaining images in gallery beyond the first 4 -->
                    <?php if (count($gallery_all) > 4): ?>
                        <div class="d-none" aria-hidden="true">
                            <?php for ($i = 4; $i < count($gallery_all); $i++):
                                $extra = $gallery_all[$i];
                                $extra_url = is_array($extra) ? (!empty($extra['sizes']['large']) ? $extra['sizes']['large'] : (!empty($extra['url']) ? $extra['url'] : '')) : (string) $extra;
                                $extra_alt = is_array($extra) ? (!empty($extra['alt']) ? $extra['alt'] : sprintf('%s %d', $alt_text, $i + 1)) : sprintf('%s %d', $alt_text, $i + 1);
                                if (!empty($extra_url)): ?>
                                    <a href="<?= esc_url($extra_url); ?>"
                                       data-fancybox="ha-media-gallery"
                                       data-caption="<?= esc_attr($extra_alt); ?>"
                                       tabindex="-1"></a>
                                <?php endif;
                            endfor; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Center Floating Badge -->
                    <div class="blog-about-collage__badge" aria-hidden="true">
                        <div class="blog-about-collage__badge-icon">
                            <?= ha_svg_icon('feature-lantern'); ?>
                        </div>
                        <span class="blog-about-collage__badge-title">
                            <?php echo esc_html($badge_title); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Text Content -->
            <div class="blog-about-section__content ha-media-carousel-section__content">
                <?php if (!empty($sub_hd)): ?>
                    <span class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-lantern'); ?></span>
                        <span class="ha-badge__text"><?= wp_kses_post($sub_hd); ?></span>
                    </span>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="ha-heading h3">
                        <?= wp_kses_post($heading); ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <div class="blog-about-section__desc ha-media-carousel-section__desc">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($append_content)): ?>
                    <div class="ha-media-carousel-section__append mt-4">
                        <?= $append_content; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
