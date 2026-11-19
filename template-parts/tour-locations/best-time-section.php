<?php
/**
 * Taxonomy Tour Locations - Best Time & Location Map Section
 *
 * Heritage Editorial + Travel Guide Experience Layout
 * Fully synchronized with Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$term      = get_queried_object();
$term_name = ($term instanceof WP_Term && !empty($term->name)) ? $term->name : '';

$best_time = ($term instanceof WP_Term) ? get_field('best_time_visit_section', $term) : [];
$map       = isset($best_time['map']) ? trim((string) $best_time['map']) : '';
$desc      = isset($best_time['description']) ? trim((string) $best_time['description']) : '';

$has_desc = !empty($desc);
$has_map  = !empty($map);

if (!$has_desc && !$has_map) {
    return;
}

// Ensure accessible title attribute on iframe if not already present
if ($has_map && stripos($map, '<iframe') !== false && stripos($map, 'title=') === false) {
    $iframe_title = !empty($term_name)
        ? sprintf(__('Interactive Map of %s', 'hoiantravel'), $term_name)
        : __('Destination Location Map', 'hoiantravel');
    $map = preg_replace(
        '/<iframe\b/i',
        '<iframe title="' . esc_attr($iframe_title) . '"',
        $map,
        1
    );
}

$grid_modifier = ($has_desc && $has_map)
    ? 'best-time-section__grid--split'
    : 'best-time-section__grid--single';
?>

<section class="ha-section best-time-section" id="location-best-time" aria-label="<?= esc_attr(!empty($term_name) ? sprintf(__('Best time to visit %s', 'hoiantravel'), $term_name) : __('Best time to visit', 'hoiantravel')); ?>">
    <div class="container">
        <div class="best-time-section__grid <?= esc_attr($grid_modifier); ?>">
            <?php if ($has_desc): ?>
                <!-- Left Column: Editorial Seasonal Travel Guide -->
                <div class="best-time-section__content">
                    <div class="content-warp best-time-section__content-inner">
                        <div class="best-time-section__badge-wrap">
                            <span class="ha-badge">
                                <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('calendar'); ?></span>
                                <span class="ha-badge__text"><?php esc_html_e('Seasonal Travel Guide', 'hoiantravel'); ?></span>
                            </span>
                        </div>

                        <h2 class="best-time-section__title ha-heading">
                            <?php esc_html_e('Best time to visit', 'hoiantravel'); ?>
                            <?php if (!empty($term_name)): ?>
                                <span><?= esc_html($term_name); ?></span>
                            <?php endif; ?>
                        </h2>

                        <div class="best-time-section__desc">
                            <?= wp_kses_post($desc); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($has_map): ?>
                <!-- Right Column: Framed Interactive Google Map Showcase -->
                <div class="best-time-section__map">
                    <div class="best-time-section__map-card">
                        <div class="best-time-section__map-header">
                            <span class="best-time-section__map-icon" aria-hidden="true">
                                <?= ha_svg_icon('map-pin'); ?>
                            </span>
                            <span class="best-time-section__map-label">
                                <?php if (!empty($term_name)): ?>
                                    <?= esc_html(sprintf(__('Location & Map — %s', 'hoiantravel'), $term_name)); ?>
                                <?php else: ?>
                                    <?php esc_html_e('Destination Location Map', 'hoiantravel'); ?>
                                <?php endif; ?>
                            </span>
                        </div>

                        <div class="best-time-section__map-frame">
                            <?= $map; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
