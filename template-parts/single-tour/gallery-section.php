<?php
/**
 * Single Tour Gallery Section
 */

$gallery_tour = get_field('gallery_tour');

if (empty($gallery_tour) || !is_array($gallery_tour)) {
    return;
}

// Normalize data once and skip invalid items
$gallery_images = [];
foreach ($gallery_tour as $image) {
    $img_data = vm_extract_gallery_image_data($image);
    if (!empty($img_data['url'])) {
        $gallery_images[] = $img_data;
    }
}

if (empty($gallery_images)) {
    return;
}

$total_images = count($gallery_images);
$visible_images_count = min(5, $total_images);
$remaining_images = max(0, $total_images - 5);
?>

<section class="tour-gallery">
    <div class="container">
        <!-- Main Gallery Container -->
        <div class="tour-gallery__main-container">
            <div class="tour-gallery__grid">
                <?php foreach ($gallery_images as $i => $img_data):
                    $is_featured = ($i === 0);
                    $is_last_visible = ($i === 4 && $remaining_images > 0);

                    $item_classes = 'tour-gallery__item';
                    if ($is_featured) {
                        $item_classes .= ' tour-gallery__item--featured';
                    }
                    if ($i >= 5) {
                        $item_classes .= ' d-md-none';
                    }
                    ?>
                    <button type="button" class="<?php echo esc_attr($item_classes); ?>" data-index="<?php echo $i; ?>" aria-label="<?php echo esc_attr('Open gallery image ' . ($i + 1)); ?>">
                        <img src="<?php echo esc_url($img_data['thumb']); ?>" alt="<?php echo esc_attr($img_data['alt']); ?>"
                            loading="<?php echo $is_featured ? 'eager' : 'lazy'; ?>" decoding="async">

                        <?php if ($is_featured): ?>
                            <div class="tour-gallery__overlay" aria-hidden="true">
                                <span class="tour-gallery__overlay-btn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <polyline points="21 15 16 10 5 21"></polyline>
                                    </svg>
                                    View Photos
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if ($is_last_visible): ?>
                            <div class="tour-gallery__counter d-none d-md-flex" aria-hidden="true">
                                <?php $photo_label = $remaining_images === 1 ? 'Photo' : 'Photos'; ?>
                                <span>+ <?php echo esc_html($remaining_images . ' ' . $photo_label); ?></span>
                            </div>
                        <?php endif; ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Mobile Gallery Navigation -->
            <?php if ($total_images > 1): ?>
                <button type="button" class="swiper-button-prev d-md-none" aria-label="Previous photo">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                        <path d="M21 12L3 12M3 12L11.5 3.5M3 12L11.5 20.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
                <button type="button" class="swiper-button-next d-md-none" aria-label="Next photo">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="currentColor">
                        <path d="M3 12L21 12M21 12L12.5 3.5M21 12L12.5 20.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            <?php endif; ?>
        </div>

        <!-- Mobile Thumbs Gallery -->
        <div class="tour-gallery__thumbs-container">
            <div class="tour-gallery__thumbs-grid">
                <?php foreach ($gallery_images as $i => $img_data): ?>
                <button type="button" class="tour-gallery__thumb-item" data-index="<?php echo $i; ?>" aria-label="<?php echo esc_attr('View thumbnail ' . ($i + 1)); ?>">
                    <img src="<?php echo esc_url($img_data['thumb']); ?>" alt="<?php echo esc_attr($img_data['alt']); ?>" loading="lazy" decoding="async">
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="tour-gallery__lightbox" id="tourGalleryLightbox" aria-hidden="true" role="dialog" aria-modal="true"
    aria-label="Image Gallery">
    <div class="tour-gallery__lightbox-overlay"></div>
    <div class="tour-gallery__lightbox-content">
        <button class="tour-gallery__lightbox-close" aria-label="Close gallery">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <button class="tour-gallery__lightbox-prev" aria-label="Previous image">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>

        <div class="tour-gallery__lightbox-image-container">
            <img src="" alt="" class="tour-gallery__lightbox-image" id="tourGalleryLightboxImg" decoding="async">
        </div>

        <button class="tour-gallery__lightbox-next" aria-label="Next image">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <div class="tour-gallery__lightbox-counter" id="tourGalleryLightboxCounter"></div>
    </div>
</div>

<!-- Gallery Data for JS -->
<script type="application/json" id="tourGalleryData">
<?php
$gallery_data_json = [];
foreach ($gallery_images as $img_data) {
    $gallery_data_json[] = [
        'url' => $img_data['url'],
        'alt' => $img_data['alt']
    ];
}
echo wp_json_encode(
    $gallery_data_json,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
);
?>
</script>