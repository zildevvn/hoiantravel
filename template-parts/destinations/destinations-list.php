<?php
$badge = get_field('badge_destinations_tpl');
$heading = get_field('heading_destinations_tpl');
$desc = get_field('desc_destinations_tpl');

$terms = get_terms(array(
    'taxonomy' => 'tour_locations',
    'hide_empty' => true,
));

$default_fallback_image = 'https://lh3.googleusercontent.com/aida/AEtjO1XOa-2n3kfHFEN18NvbixXY_CUXbTZlchtFKuZ-Ja-QQ9mUlW2KQxBaqTTzcly6J6Kv57UarQeCKZRgofffJ957OZjFM3TMVs_1j37Mm1oaIjqFNoaTOfnd_CXU_znxbClN4kyX5ukJCniJpfQu3QFFgkldP4Crk4CxGZWb5phrVKjGnJ7JRoCTV8uTpRJlt6A6ZrfqlH3-y-qfPv8v6SAa50PlKEajY7gKOfJkrQ';
?>
<section class="destinations-list-section ha-section">
    <div class="container">
        <?php if ($badge || $heading || $desc): ?>
            <div class="destinations-list-intro text-center">
                <?php if ($badge): ?>
                    <span class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= esc_html($badge); ?>
                    </span>
                <?php endif; ?>
                <?php if ($heading): ?>
                    <h2 class="ha-heading destinations-list-intro__title">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php endif; ?>
                <?php if ($desc): ?>
                    <p class="ha-sub-heading destinations-list-intro__desc">
                        <?= esc_html($desc); ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($terms) && !is_wp_error($terms)): ?>
            <div class="destinations-list-section__grid">
                <?php foreach ($terms as $term): ?>
                    <?php
                    // ACF Image extraction (supports array, numeric attachment ID, or URL string)
                    $term_image = function_exists('get_field') ? get_field('image', $term) : null;
                    $term_custom_heading = function_exists('get_field')
                        ? get_field('custom_heading', $term)
                        : '';

                    $term_heading = $term_custom_heading ?: $term->name;
                    $image_url = '';
                    $image_alt = $term->name;

                    if (is_array($term_image)) {
                        $image_url = !empty($term_image['sizes']['large'])
                            ? $term_image['sizes']['large']
                            : (!empty($term_image['url']) ? $term_image['url'] : '');
                        if (!empty($term_image['alt'])) {
                            $image_alt = $term_image['alt'];
                        }
                    } elseif (is_numeric($term_image)) {
                        $image_url = wp_get_attachment_image_url((int) $term_image, 'large');
                        $alt = get_post_meta((int) $term_image, '_wp_attachment_image_alt', true);
                        if (!empty($alt)) {
                            $image_alt = $alt;
                        }
                    } elseif (is_string($term_image) && !empty($term_image)) {
                        $image_url = $term_image;
                    }

                    if (empty($image_url)) {
                        $image_url = $default_fallback_image;
                    }

                    // Permalink extraction (checks custom_link_page ACF field, then falls back to term archive)
                    $custom_link = function_exists('get_field') ? get_field('custom_link_page', $term) : '';
                    $term_link = !empty($custom_link) ? $custom_link : get_term_link($term);
                    if (is_wp_error($term_link)) {
                        $term_link = '#';
                    }

                    // Tour count badge
                    $tour_count = isset($term->count) ? (int) $term->count : 0;
                    $tours_badge = $tour_count > 0
                        ? sprintf(_n('%d Tour', '%d Tours', $tour_count, 'hoiantravel'), $tour_count)
                        : __('Explore', 'hoiantravel');

                    // Description excerpt
                    $term_desc = !empty($term->description) ? wp_trim_words($term->description, 16, '...') : '';
                    ?>
                    <a href="<?= esc_url($term_link); ?>" class="destination-card"
                        aria-label="<?= esc_attr(sprintf(__('Explore %s destination', 'hoiantravel'), $term->name)); ?>">
                        <div class="destination-card__media">
                            <img src="<?= esc_url($image_url); ?>" alt="<?= esc_attr($image_alt); ?>" loading="lazy" width="600"
                                height="375" />
                            <div class="destination-card__overlay"></div>
                            <span class="destination-card__badge">
                                <?= ha_svg_icon('location'); ?>
                                <span><?= esc_html($tours_badge); ?></span>
                            </span>
                        </div>

                        <div class="destination-card__body">
                            <div class="destination-card__header">
                                <h3 class="destination-card__title m-0 h5">
                                    <?= esc_html($term_heading); ?>
                                </h3>
                            </div>

                            <?php if (!empty($term_desc)): ?>
                                <p class="destination-card__desc">
                                    <?= esc_html($term_desc); ?>
                                </p>
                            <?php endif; ?>

                            <div class="destination-card__footer">
                                <span class="destination-card__action ha-button">
                                    <span><?= esc_html__('Explore Destination', 'hoiantravel'); ?></span>
                                    <?= ha_svg_icon('arrow-right'); ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="destinations-empty text-center">
                <div class="destinations-empty__icon">
                    <?= ha_svg_icon('location'); ?>
                </div>
                <h3 class="destinations-empty__title"><?= esc_html__('No destinations found', 'hoiantravel'); ?></h3>
                <p class="destinations-empty__desc">
                    <?= esc_html__('Please check back later as new destinations are being added.', 'hoiantravel'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>