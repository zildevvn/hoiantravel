<?php
/**
 * Single Car Itinerary Section
 *
 * Clean vertical timeline with time markers, route steps and detailed descriptions.
 * Fetches data strictly from ACF Group field: `itinerary_car`.
 * Master switch: `enable_itinerary_paragraph` directly inside Group `itinerary_car`.
 *
 * @package hoiantravel
 */

$itinerary = get_field('itinerary_car');

if (empty($itinerary) || !is_array($itinerary)) {
    return;
}

// 1. Read master paragraph toggle directly from the Group field
$enable_paragraph = !empty($itinerary['enable_itinerary_paragraph']);

// 2. Read sub-fields
$time_line = (!empty($itinerary['itinerary_list']) && is_array($itinerary['itinerary_list'])) ? $itinerary['itinerary_list'] : [];
$group_paragraph = $itinerary['itinerary_paragraph'] ?? ($itinerary['itinerary_desc'] ?? '');
$itinerary_img = $itinerary['img'] ?? '';

// 3. Check if there are valid items to display
$has_timeline = false;
if (!empty($time_line)) {
    foreach ($time_line as $item) {
        $title = $item['title'] ?? '';
        $time = $item['time'] ?? '';
        $desc = $item['description'] ?? ($item['paragraph'] ?? '');
        if (!empty($title) || !empty($time) || (!empty($desc) && $enable_paragraph)) {
            $has_timeline = true;
            break;
        }
    }
}

$has_intro_paragraph = $enable_paragraph && !empty($group_paragraph);

if (!$has_timeline && !$has_intro_paragraph && empty($itinerary_img)) {
    return;
}
?>

<section id="car-itinerary" class="tour-section-block tour-itinerary car-itinerary-block">
    <header class="tour-section-header">
        <h2 class="ha-heading tour-section-title h3">
            <?php esc_html_e('Trip Itinerary & Route', 'hoiantravel'); ?>
        </h2>
    </header>

    <?php if ($has_intro_paragraph): ?>
        <div class="tour-itinerary__intro single-tour-prose">
            <?= wp_kses_post(wpautop($group_paragraph)); ?>
        </div>
    <?php endif; ?>

    <?php if ($has_timeline): ?>
        <div class="tour-itinerary__timeline">
            <?php
            $step_index = 0;
            foreach ($time_line as $item):
                $time = $item['time'] ?? '';
                $title = $item['title'] ?? '';
                $paragraph = $item['description'] ?? ($item['paragraph'] ?? '');

                // Skip items that have neither title, time nor visible paragraph
                if (empty($title) && empty($time) && (!$enable_paragraph || empty($paragraph))) {
                    continue;
                }
                $step_index++;
                ?>
                <div class="itinerary-node">
                    <!-- Left: Time Marker / Step Number -->
                    <div class="itinerary-node__meta">
                        <?php if (!empty($time)): ?>
                            <span class="itinerary-node__time">
                                <?= ha_svg_icon('clock'); ?>
                                <?= esc_html($time); ?>
                            </span>
                        <?php else: ?>
                            <span class="itinerary-node__step-num">
                                <?php printf(esc_html__('Stop %02d', 'hoiantravel'), $step_index); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Center: Timeline Spine & Dot -->
                    <div class="itinerary-node__spine" aria-hidden="true">
                        <div class="itinerary-node__dot"></div>
                        <div class="itinerary-node__line"></div>
                    </div>

                    <!-- Right: Content Details -->
                    <div class="itinerary-node__body">
                        <?php if (!empty($title)): ?>
                            <h3 class="itinerary-node__title">
                                <?= esc_html($title); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!$enable_paragraph && !empty($paragraph)): ?>
                            <div class="itinerary-node__desc single-tour-prose">
                                <?= wp_kses_post(wpautop($paragraph)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($itinerary_img)): ?>
        <div class="tour-itinerary__media mt-4">
            <img src="<?= esc_url($itinerary_img); ?>"
                alt="<?php echo esc_attr(sprintf(__('Route map and stops for %s', 'hoiantravel'), get_the_title())); ?>"
                loading="lazy" decoding="async" class="img-fluid rounded" />
        </div>
    <?php endif; ?>
</section>