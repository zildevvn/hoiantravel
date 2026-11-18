<?php
/**
 * About Page - Steps Booking Section
 *
 * Modern Step-by-Step Experience Booking Journey
 * Fully synchronized with Hoi An Travel Design System
 *
 * @package hoiantravel
 */

$heading = get_field('hd_steps_bk_ab_tpl');
$sub_heading = get_field('sub_heading_steps_bk_ab_tpl');
$steps = get_field('steps_list_ab_tpl');
$step_count = (!empty($steps) && is_array($steps)) ? count($steps) : 0;

// Fallback curated SVG icons for standard 3 or 4-step travel booking flow
$default_step_icons = [
    0 => 'search-icon',      // Step 1: Discover & Select Tour / Vehicle
    1 => 'calendar',         // Step 2: Choose Dates & Group Details
    2 => 'credit-card',      // Step 3: Secure Confirmation & Payment
    3 => 'feature-compass',  // Step 4: Seamless Travel & Local Experience
];

if (!empty($steps) && is_array($steps)):
    ?>
    <section class="ha-section steps-booking-section"
        aria-label="<?php esc_attr_e('How to Book Your Experience with Hoi An Travel', 'hoiantravel'); ?>">
        <div class="container">
            <!-- Section Header -->
            <div class="steps-booking-section__header text-center">
                <div class="steps-booking-section__badge-wrap">
                    <span class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                        <span class="ha-badge__text"><?php esc_html_e('Easy Booking Flow', 'hoiantravel'); ?></span>
                    </span>
                </div>

                <?php if (!empty($heading)): ?>
                    <h2 class="steps-booking-section__title ha-heading">
                        <?= esc_html($heading); ?>
                    </h2>
                <?php else: ?>
                    <h2 class="steps-booking-section__title ha-heading">
                        <?php esc_html_e('How to Book Your Journey', 'hoiantravel'); ?>
                    </h2>
                <?php endif; ?>
            </div>

            <!-- Steps Process Flow List -->
            <div class="steps-booking-section__grid steps-booking-section__grid--cols-<?= esc_attr($step_count); ?>"
                style="--step-count: <?= esc_attr($step_count); ?>;">
                <?php foreach ($steps as $key => $step): ?>
                    <?php
                    $step_number = sprintf('%02d', $key + 1);
                    $step_heading = $step['heading'] ?? '';
                    $step_desc = $step['description'] ?? '';
                    $step_icon_url = $step['icon'] ?? '';
                    $fallback_icon = $default_step_icons[$key % count($default_step_icons)] ?? 'check';
                    ?>
                    <div class="ha-step-card">
                        <!-- Step Indicator: Number + Icon -->
                        <div class="ha-step-card__indicator">
                            <div class="ha-step-card__icon-box">
                                <?php if (!empty($step_icon_url)): ?>
                                    <img src="<?= esc_url($step_icon_url); ?>"
                                        alt="<?= esc_attr($step_heading ?: sprintf(__('Step %s', 'hoiantravel'), $step_number)); ?>"
                                        class="ha-step-card__icon-img" loading="lazy" />
                                <?php else: ?>
                                    <span class="ha-step-card__icon-svg" aria-hidden="true">
                                        <?= ha_svg_icon($fallback_icon); ?>
                                    </span>
                                <?php endif; ?>

                                <!-- Step Pill Badge -->
                                <span class="ha-step-card__number"
                                    aria-label="<?= esc_attr(sprintf(__('Step %s', 'hoiantravel'), $step_number)); ?>">
                                    <?= esc_html($step_number); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Step Body Card -->
                        <div class="ha-step-card__content">
                            <?php if (!empty($step_heading)): ?>
                                <h3 class="ha-step-card__title">
                                    <?= esc_html($step_heading); ?>
                                </h3>
                            <?php endif; ?>

                            <?php if (!empty($step_desc)): ?>
                                <p class="ha-step-card__desc">
                                    <?= nl2br(esc_html($step_desc)); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>