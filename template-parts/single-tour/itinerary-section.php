<?php
/**
 * Single Tour Itinerary Section
 *
 * Clean vertical timeline with time markers, step titles and detailed descriptions.
 *
 * @package hoiantravel
 */

$itinerary_tour = get_field('itinerary_tour');

if (empty($itinerary_tour) || !is_array($itinerary_tour)) {
    return;
}

$time_line = $itinerary_tour['itinerary_list'] ?? [];
$itinerary_desc = $itinerary_tour['itinerary_desc'] ?? '';
$itinerary_img = $itinerary_tour['img'] ?? '';

if (empty($time_line) && empty($itinerary_desc) && empty($itinerary_img)) {
    return;
}
?>

<div id="tour-itinerary" class="tour-section-block tour-itinerary">
    <div class="tour-section-header">
        <h2 class="ha-heading tour-section-title h3">
            <?php esc_html_e('Tour Itinerary', 'ha'); ?>
        </h2>
    </div>

    <?php if (!empty($itinerary_desc)): ?>
        <div class="tour-itinerary__intro">
            <?= wp_kses_post(wpautop($itinerary_desc)); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($time_line) && is_array($time_line)): ?>
        <div class="tour-itinerary__timeline">
            <?php foreach ($time_line as $index => $item):
                $time = $item['time'] ?? '';
                $title = $item['title'] ?? '';
                $description = $item['description'] ?? '';
                if (empty($title) && empty($description)) {
                    continue;
                }
                ?>
                <div class="itinerary-node">
                    <!-- Left: Time Marker -->
                    <div class="itinerary-node__meta">
                        <?php if ($time): ?>
                            <span class="itinerary-node__time">
                                <?= ha_svg_icon('clock'); ?>
                                <?= esc_html($time); ?>
                            </span>
                        <?php else: ?>
                            <span class="itinerary-node__step-num">
                                <?php printf(esc_html__('Stop %02d', 'ha'), $index + 1); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Center: Timeline Line & Indicator Dot -->
                    <div class="itinerary-node__spine" aria-hidden="true">
                        <div class="itinerary-node__dot"></div>
                        <div class="itinerary-node__line"></div>
                    </div>

                    <!-- Right: Content Details -->
                    <div class="itinerary-node__body">
                        <?php if ($title): ?>
                            <h3 class="itinerary-node__title">
                                <?= esc_html($title); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($description): ?>
                            <div class="itinerary-node__desc">
                                <?= wp_kses_post(wpautop($description)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($itinerary_img)): ?>
        <div class="tour-itinerary__media">
            <img src="<?= esc_url($itinerary_img); ?>"
                alt="<?php echo esc_attr(sprintf(__('Itinerary map and highlights for %s', 'ha'), get_the_title())); ?>"
                loading="lazy" decoding="async" />
        </div>
    <?php endif; ?>
</div>