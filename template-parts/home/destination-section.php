<?php
$heading = get_field('heading_destination_home');
$badge = get_field('badge_destination_home');
$button = get_field('btn_destination_home');
$desc = get_field('desc_destination_home');
$action = get_field('action_destination_home');
?>
<section class="ha-section destination-section" id="destination-hub-section">
    <div aria-hidden="true" class="destination-section__ambient">
        <div class="destination-section__ribbon"
            style="background: linear-gradient(90deg, rgb(30, 77, 72), rgb(223, 161, 40), rgb(30, 77, 72));">
            <?php echo ha_svg_icon('indochine-ribbon'); ?>
        </div>

        <div class="destination-section__vignette" style="box-shadow: rgba(36, 28, 26, 0.06) 0px 0px 100px inset;">
        </div>
        <?php echo ha_svg_icon('destination-lanterns'); ?>
        <div class="destination-section__watermark">
            <?php echo ha_svg_icon('chua-cau-bridge'); ?>
        </div>
    </div>

    <div class="container">
        <div class="destination-section__intro">
            <?php if (!empty($badge)): ?>
                <div class="ha-badge">
                    <?php echo ha_svg_icon('badge'); ?>
                    <?= $badge ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($heading)): ?>
                <h2> <?= $heading ?> </h2>
            <?php endif; ?>

            <?php if (!empty($desc)): ?>
                <p class="mt-3">
                    <?= $desc ?>
                </p>
            <?php endif; ?>
        </div>

        <?php
        $terms = get_terms([
            'taxonomy' => 'tour_locations',
        ]);
        ?>

        <?php if (!is_wp_error($terms) && !empty($terms)): ?>
            <?php
            $first_term = $terms[0];

            // Determine initial preview image: use first term with a valid image if available
            $first_image_url = '';
            foreach ($terms as $t) {
                $img = function_exists('get_field') ? get_field('image', $t) : null;
                $url = '';
                if (is_array($img)) {
                    $url = !empty($img['url']) ? $img['url'] : (!empty($img['sizes']['large']) ? $img['sizes']['large'] : '');
                } elseif (is_numeric($img)) {
                    $url = wp_get_attachment_image_url((int) $img, 'full');
                } elseif (is_string($img)) {
                    $url = $img;
                }
                if (!empty($url)) {
                    $first_image_url = $url;
                    break;
                }
            }

            if (empty($first_image_url)) {
                $first_image_url = 'https://lh3.googleusercontent.com/aida/AEtjO1XOa-2n3kfHFEN18NvbixXY_CUXbTZlchtFKuZ-Ja-QQ9mUlW2KQxBaqTTzcly6J6Kv57UarQeCKZRgofffJ957OZjFM3TMVs_1j37Mm1oaIjqFNoaTOfnd_CXU_znxbClN4kyX5ukJCniJpfQu3QFFgkldP4Crk4CxGZWb5phrVKjGnJ7JRoCTV8uTpRJlt6A6ZrfqlH3-y-qfPv8v6SAa50PlKEajY7gKOfJkrQ';
            }

            $first_custom_link = function_exists('get_field') ? get_field('custom_link_page', $first_term) : '';
            $first_term_link = !empty($first_custom_link) ? $first_custom_link : get_term_link($first_term);
            if (is_wp_error($first_term_link)) {
                $first_term_link = '#';
            }

            $first_tour_count = isset($first_term->count) ? (int) $first_term->count : 0;
            $first_badge_text = sprintf(_n('%d TOUR', '%d TOURS', $first_tour_count, 'hoiantravel'), $first_tour_count);
            $first_cta_text = $first_tour_count > 0
                ? sprintf(__('Browse all %d private & small group itineraries →', 'hoiantravel'), $first_tour_count)
                : __('Browse all private & small group itineraries →', 'hoiantravel');
            ?>

            <!-- 2-COLUMN EDITORIAL & SHOWCASE GRID -->
            <div class="destination-section__grid">
                <!-- LEFT COLUMN: The Editorial Destination List -->
                <div class="destination-section__list" id="destinations-editorial-list" role="tablist"
                    aria-label="<?php esc_attr_e('Destinations List', 'hoiantravel'); ?>">
                    <?php foreach ($terms as $index => $term): ?>
                        <?php
                        $is_active = ($index === 0);

                        $term_image = function_exists('get_field') ? get_field('image', $term) : null;
                        $term_image_url = '';
                        if (is_array($term_image)) {
                            $term_image_url = !empty($term_image['url']) ? $term_image['url'] : (!empty($term_image['sizes']['large']) ? $term_image['sizes']['large'] : '');
                        } elseif (is_numeric($term_image)) {
                            $term_image_url = wp_get_attachment_image_url((int) $term_image, 'full');
                        } elseif (is_string($term_image)) {
                            $term_image_url = $term_image;
                        }

                        $custom_link = function_exists('get_field') ? get_field('custom_link_page', $term) : '';
                        $term_link = !empty($custom_link) ? $custom_link : get_term_link($term);
                        if (is_wp_error($term_link)) {
                            $term_link = '#';
                        }

                        $tour_count = isset($term->count) ? (int) $term->count : 0;
                        $tours_badge = sprintf(_n('%d Tour Available', '%d Tours Available', $tour_count, 'hoiantravel'), $tour_count);
                        $preview_badge = sprintf(_n('%d TOUR', '%d TOURS', $tour_count, 'hoiantravel'), $tour_count);
                        $cta_text = $tour_count > 0
                            ? sprintf(__('Browse all %d private & small group itineraries →', 'hoiantravel'), $tour_count)
                            : __('Browse all private & small group itineraries →', 'hoiantravel');
                        ?>
                        <div class="destination-row <?= $is_active ? 'active is-active' : '' ?>"
                            data-term-id="<?= esc_attr($term->term_id) ?>" data-dest="<?= esc_attr($term->slug) ?>"
                            data-image="<?= esc_url($term_image_url) ?>" data-title="<?= esc_attr($term->name) ?>"
                            data-desc="<?= esc_attr($term->description) ?>" data-badge="<?= esc_attr($preview_badge) ?>"
                            data-cta="<?= esc_attr($cta_text) ?>" data-link="<?= esc_url($term_link) ?>" tabindex="0"
                            role="button" aria-selected="<?= $is_active ? 'true' : 'false' ?>"
                            aria-label="<?= esc_attr($term->name) ?>" aria-controls="destination-preview-card">
                            <div class="dest-info">
                                <span class="dest-number"><?= esc_html(str_pad($index + 1, 2, '0', STR_PAD_LEFT)) ?></span>
                                <div class="dest-content">
                                    <h3 class="dest-title h4"><?= esc_html($term->name) ?></h3>
                                    <?php if (!empty($term->description)): ?>
                                        <span class="dest-desc"><?= esc_html($term->description) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="dest-meta">
                                <span class="dest-badge"><?= esc_html($tours_badge) ?></span>
                                <div class="dest-arrow">
                                    <?php echo ha_svg_icon('dest-arrow'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- RIGHT COLUMN: High-Resolution Showcase Card -->
                <div class="destination-section__preview">
                    <div class="destination-preview-card" id="destination-preview-card">
                        <img alt="<?= esc_attr($first_term->name) ?>" class="destination-preview-card__image"
                            id="preview-image" src="<?= esc_url($first_image_url) ?>" />
                        <div class="destination-preview-card__overlay"></div>
                        <!-- Top Badges -->
                        <div class="destination-preview-card__badges">
                            <span class="destination-preview-card__badge-count"
                                id="preview-badge"><?= esc_html($first_badge_text) ?></span>
                        </div>
                        <!-- Bottom Content -->
                        <div class="destination-preview-card__content">
                            <h3 class="destination-preview-card__title h4" id="preview-title">

                                <?= esc_html($first_term->name) ?>
                            </h3>
                            <?php if (!empty($first_term->description)): ?>

                                <p class="destination-preview-card__desc" id="preview-desc">
                                    <?= esc_html($first_term->description) ?>
                                </p>
                            <?php endif; ?>
                            <a class="destination-preview-card__cta" href="<?= esc_url($first_term_link) ?>"
                                id="preview-cta">
                                <?= esc_html($first_cta_text) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- BOTTOM INVITATION BAR -->
        <div class="destination-section__invitation">
            <div class="destination-section__invitation-box">
                <?php if (!empty($action)): ?>
                    <span class="destination-section__invitation-text">
                        <?= $action ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($button)): ?>
                    <a href="<?= $button['url'] ?>" target="<?= $button['target'] ?>" class="ha-button">
                        <?= $button['title'] ?>
                        <?php echo ha_svg_icon('arrow_next'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- END: AmbientBottomMeta -->