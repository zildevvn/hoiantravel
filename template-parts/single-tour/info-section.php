<?php
$location_tour = get_field('location_tour');
$time_tour = get_field('time_tour');
$price_tour = get_field('price_tour');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');
$paxs_tours = get_field('paxs_tours');
$min_pax = isset($paxs_tours['min']) ? $paxs_tours['min'] : '';
$max_pax = isset($paxs_tours['max']) ? $paxs_tours['max'] : '';

?>
<section class="ha-tour-info ha-section">
    <div class="container">
        <?php if ($location_tour || $time_tour || $price_tour || ($min_pax && $max_pax)): ?>
            <div class="tour-info-card">
                <?php if ($location_tour): ?>
                    <div class="info-item">
                        <div class="info-icon">
                            <?= ha_svg_icon('map-pin'); ?>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Location</span>
                            <span class="info-value"><?php echo esc_html($location_tour); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($time_tour): ?>
                    <div class="info-item">
                        <div class="info-icon">
                            <?= ha_svg_icon('clock'); ?>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Duration</span>
                            <span class="info-value"><?php echo esc_html($time_tour); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($min_pax && $max_pax): ?>
                    <div class="info-item">
                        <div class="info-icon">
                            <?= ha_svg_icon('users'); ?>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Group Size</span>
                            <span class="info-value"><?php echo esc_html($min_pax) . '&ndash;' . esc_html($max_pax); ?>
                                guests</span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($price_tour): ?>
                    <div class="info-item">
                        <div class="info-icon">
                            <?= ha_svg_icon('dollar-sign'); ?>
                        </div>
                        <div class="info-content">
                            <span class="info-label">Price</span>
                            <span
                                class="info-value price-value"><?= esc_html(ha_format_price($price_tour, $currency)); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        $gallery_tour = get_field('gallery_tour');
        if ($gallery_tour && is_array($gallery_tour)):
            $image_count = count($gallery_tour);
            ?>
            <div class="tour-gallery">
                <div class="tour-gallery-grid <?php echo 'grid-count-' . min($image_count, 5); ?>">
                    <?php
                    $display_count = min($image_count, 5); // Display up to 5 images
                
                    for ($i = 0; $i < $display_count; $i++):
                        $image = $gallery_tour[$i];
                        $img_url = is_array($image) ? $image['url'] : (is_numeric($image) ? wp_get_attachment_url($image) : $image);
                        $img_alt = is_array($image) && !empty($image['alt']) ? $image['alt'] : get_the_title();
                        // Try to use 'large' size if available for the grid
                        $img_thumb = is_array($image) && isset($image['sizes']['large']) ? $image['sizes']['large'] : $img_url;
                        ?>
                        <div class="tour-gallery-item">
                            <a href="<?php echo esc_url($img_url); ?>" data-fancybox="tour-gallery"
                                data-caption="<?php echo esc_attr($img_alt); ?>">
                                <img src="<?php echo esc_url($img_thumb); ?>" alt="<?php echo esc_attr($img_alt); ?>"
                                    loading="lazy">
                                <div class="tour-gallery-overlay"></div>
                            </a>
                        </div>
                    <?php endfor; ?>
                </div>

                <?php if ($image_count > 1): ?>
                    <button class="btn-view-all" onclick="document.querySelector('.tour-gallery-item a').click();">
                        <?= ha_svg_icon('image'); ?>
                        View All Photos
                    </button>
                <?php endif; ?>

                <?php
                // Render the rest of the images hidden so Fancybox can loop them
                if ($image_count > 5):
                    for ($i = 5; $i < $image_count; $i++):
                        $image = $gallery_tour[$i];
                        $img_url = is_array($image) ? $image['url'] : (is_numeric($image) ? wp_get_attachment_url($image) : $image);
                        $img_alt = is_array($image) && !empty($image['alt']) ? $image['alt'] : get_the_title();
                        ?>
                        <a href="<?php echo esc_url($img_url); ?>" data-fancybox="tour-gallery"
                            data-caption="<?php echo esc_attr($img_alt); ?>" style="display: none;"></a>
                    <?php endfor;
                endif;
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>