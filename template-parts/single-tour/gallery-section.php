<?php
/**
 * Single Tour Gallery Section — Visual Opening
 *
 * 5-Image Showcase Grid (2 Left - 3 Right):
 * - Left column: 2 stacked equal-height images (Bottom-left carries the Photos badge)
 * - Right column: 1 wide top image + 2 split bottom images (Bottom-right carries +X more overlay)
 * - Mobile responsive: Only 1 primary image with Photos badge, followed by smooth horizontal swipe tab bar
 * - Lightbox: Full Fancybox 5 integration for all images
 *
 * @package hoiantravel
 */

$gallery_tour = get_field('gallery_tour');

if (empty($gallery_tour) || !is_array($gallery_tour)) {
    return;
}

// Normalize gallery data
$gallery_images = [];
foreach ($gallery_tour as $image) {
    $img_data = ha_extract_gallery_image_data($image);
    if (!empty($img_data['url'])) {
        $gallery_images[] = $img_data;
    }
}

if (empty($gallery_images)) {
    return;
}

$total_images = count($gallery_images);
$page_title   = get_the_title();

// Extract up to 5 visible images
$img_1 = $gallery_images[0] ?? null;
$img_2 = $gallery_images[1] ?? null;
$img_3 = $gallery_images[2] ?? null;
$img_4 = $gallery_images[3] ?? null;
$img_5 = $gallery_images[4] ?? null;

$remaining_count = max(0, $total_images - 5);
?>

<section class="tour-gallery-opening" id="tour-gallery" aria-label="<?php esc_attr_e('Tour Photo Gallery', 'ha'); ?>">
    <div class="container">
        <div class="tour-gallery-opening__wrapper">
            <!-- 5-Image Grid: 2 Left Stacked - 3 Right (1 Top Wide + 2 Bottom Split) -->
            <div class="tour-gallery-opening__grid">

                <!-- 1. LEFT COLUMN: 2 Stacked Images -->
                <div class="tour-gallery-opening__col tour-gallery-opening__col--left">
                    <!-- Photo 1: Top-Left (Primary image shown on all viewports, sole image on mobile) -->
                    <?php if (!empty($img_1)): ?>
                        <div class="tour-gallery-opening__item tour-gallery-opening__item--left-top">
                            <a href="<?php echo esc_url($img_1['url']); ?>"
                                class="tour-gallery-opening__link"
                                data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_1['alt'] ?: sprintf(__('%s — Photo 1 of %d', 'ha'), $page_title, $total_images)); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Open photo 1 of %d: %s', 'ha'), $total_images, $img_1['alt'] ?: $page_title)); ?>">
                                <img src="<?php echo esc_url($img_1['url']); ?>"
                                    alt="<?php echo esc_attr($img_1['alt'] ?: $page_title); ?>"
                                    loading="eager"
                                    decoding="async">

                                <!-- Mobile badge positioned at bottom-left of the single mobile photo -->
                                <div class="tour-gallery-opening__badge d-md-none" aria-hidden="true">
                                    <span class="tour-gallery-opening__badge-inner">
                                        <?= ha_svg_icon('camera'); ?>
                                        <span><?php printf(esc_html(_n('%d Photo', '%d Photos', $total_images, 'ha')), $total_images); ?></span>
                                    </span>
                                </div>

                                <div class="tour-gallery-opening__zoom-hint" aria-hidden="true">
                                    <span class="tour-gallery-opening__zoom-hint-btn">
                                        <?= ha_svg_icon('search-icon'); ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Photo 2: Bottom-Left (Desktop/Tablet only; carries the bottom-left Photos badge) -->
                    <?php if (!empty($img_2)): ?>
                        <div class="tour-gallery-opening__item tour-gallery-opening__item--left-bottom d-none d-md-block">
                            <a href="<?php echo esc_url($img_2['url']); ?>"
                                class="tour-gallery-opening__link"
                                data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_2['alt'] ?: sprintf(__('%s — Photo 2 of %d', 'ha'), $page_title, $total_images)); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Open photo 2 of %d: %s', 'ha'), $total_images, $img_2['alt'] ?: $page_title)); ?>">
                                <img src="<?php echo esc_url($img_2['thumb'] ?: $img_2['url']); ?>"
                                    alt="<?php echo esc_attr($img_2['alt'] ?: sprintf(__('%s photo 2', 'ha'), $page_title)); ?>"
                                    loading="lazy"
                                    decoding="async">

                                <!-- Desktop badge placed at bottom-left of the bottom-left photo -->
                                <div class="tour-gallery-opening__badge" aria-hidden="true">
                                    <span class="tour-gallery-opening__badge-inner">
                                        <?= ha_svg_icon('camera'); ?>
                                        <span><?php printf(esc_html(_n('%d Photo', '%d Photos', $total_images, 'ha')), $total_images); ?></span>
                                    </span>
                                </div>

                                <div class="tour-gallery-opening__zoom-hint" aria-hidden="true">
                                    <span class="tour-gallery-opening__zoom-hint-btn">
                                        <?= ha_svg_icon('search-icon'); ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. RIGHT COLUMN: 3 Images (1 Top Wide + 2 Bottom Split) — Desktop/Tablet only -->
                <div class="tour-gallery-opening__col tour-gallery-opening__col--right d-none d-md-grid">
                    <!-- Photo 3: Top Wide Image -->
                    <?php if (!empty($img_3)): ?>
                        <div class="tour-gallery-opening__item tour-gallery-opening__item--right-top">
                            <a href="<?php echo esc_url($img_3['url']); ?>"
                                class="tour-gallery-opening__link"
                                data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_3['alt'] ?: sprintf(__('%s — Photo 3 of %d', 'ha'), $page_title, $total_images)); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Open photo 3 of %d: %s', 'ha'), $total_images, $img_3['alt'] ?: $page_title)); ?>">
                                <img src="<?php echo esc_url($img_3['thumb'] ?: $img_3['url']); ?>"
                                    alt="<?php echo esc_attr($img_3['alt'] ?: sprintf(__('%s photo 3', 'ha'), $page_title)); ?>"
                                    loading="lazy"
                                    decoding="async">

                                <div class="tour-gallery-opening__zoom-hint" aria-hidden="true">
                                    <span class="tour-gallery-opening__zoom-hint-btn">
                                        <?= ha_svg_icon('search-icon'); ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Photo 4: Bottom-Left Image -->
                    <?php if (!empty($img_4)): ?>
                        <div class="tour-gallery-opening__item tour-gallery-opening__item--right-bottom-1">
                            <a href="<?php echo esc_url($img_4['url']); ?>"
                                class="tour-gallery-opening__link"
                                data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_4['alt'] ?: sprintf(__('%s — Photo 4 of %d', 'ha'), $page_title, $total_images)); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Open photo 4 of %d: %s', 'ha'), $total_images, $img_4['alt'] ?: $page_title)); ?>">
                                <img src="<?php echo esc_url($img_4['thumb'] ?: $img_4['url']); ?>"
                                    alt="<?php echo esc_attr($img_4['alt'] ?: sprintf(__('%s photo 4', 'ha'), $page_title)); ?>"
                                    loading="lazy"
                                    decoding="async">

                                <div class="tour-gallery-opening__zoom-hint" aria-hidden="true">
                                    <span class="tour-gallery-opening__zoom-hint-btn">
                                        <?= ha_svg_icon('search-icon'); ?>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Photo 5: Bottom-Right Image (with +X more photos overlay if > 5) -->
                    <?php if (!empty($img_5)): ?>
                        <div class="tour-gallery-opening__item tour-gallery-opening__item--right-bottom-2">
                            <a href="<?php echo esc_url($img_5['url']); ?>"
                                class="tour-gallery-opening__link"
                                data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_5['alt'] ?: sprintf(__('%s — Photo 5 of %d', 'ha'), $page_title, $total_images)); ?>"
                                aria-label="<?php echo esc_attr(sprintf(__('Open photo 5 of %d: %s', 'ha'), $total_images, $img_5['alt'] ?: $page_title)); ?>">
                                <img src="<?php echo esc_url($img_5['thumb'] ?: $img_5['url']); ?>"
                                    alt="<?php echo esc_attr($img_5['alt'] ?: sprintf(__('%s photo 5', 'ha'), $page_title)); ?>"
                                    loading="lazy"
                                    decoding="async">

                                <?php if ($remaining_count > 0): ?>
                                    <div class="tour-gallery-opening__more-overlay" aria-hidden="true">
                                        <span class="tour-gallery-opening__more-icon"><?= ha_svg_icon('camera'); ?></span>
                                        <span class="tour-gallery-opening__more-count">+<?php echo esc_html($remaining_count); ?></span>
                                        <span class="tour-gallery-opening__more-label"><?php esc_html_e('more photos', 'ha'); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="tour-gallery-opening__zoom-hint" aria-hidden="true">
                                        <span class="tour-gallery-opening__zoom-hint-btn">
                                            <?= ha_svg_icon('search-icon'); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Hidden anchor links for photos 6+ so Fancybox allows browsing the complete album -->
            <?php if ($total_images > 5): ?>
                <div class="d-none" aria-hidden="true">
                    <?php for ($i = 5; $i < $total_images; $i++):
                        $hidden_img = $gallery_images[$i];
                        $hidden_caption = $hidden_img['alt'] ?: sprintf(__('%s — Photo %d of %d', 'ha'), $page_title, $i + 1, $total_images);
                        ?>
                        <a href="<?php echo esc_url($hidden_img['url']); ?>"
                            data-fancybox="tour-gallery"
                            data-caption="<?php echo esc_attr($hidden_caption); ?>"
                            tabindex="-1"></a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
