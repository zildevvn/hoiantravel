<?php
/**
 * Single Tour Booking Sidebar Card
 *
 * Sticky booking component with price highlight, guest selector, date picker,
 * and clear CTA action. Preserves all existing IDs and JS handlers.
 *
 * @package hoiantravel
 */

$price_tour = get_field('price_tour');
$price_group = get_field('price_for_group_tour');
$price_private = get_field('price_for_private_tour');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

$is_car_tour = has_term('Car Tours', 'tour_cats', get_the_ID());
$is_private_only = empty($price_group) && !empty($price_private);
?>

<div id="form-booking-tour" class="ha-form-booking">
    <!-- Header with Pricing and Rating -->
    <div class="ha-form-booking__header">
        <div class="ha-form-booking__price-box">
            <span class="ha-form-booking__price-label"><?php esc_html_e('From', 'ha'); ?></span>
            <div class="ha-form-booking__price-wrap">
                <span class="ha-form-booking__price-val">
                    <?= esc_html(ha_format_price($price_tour ? $price_tour : 100, $currency)); ?>
                </span>
                <?php if (!$is_private_only && !$is_car_tour): ?>
                    <span class="ha-form-booking__price-unit">/ <?php esc_html_e('person', 'ha'); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="ha-form-booking__rating-box">
            <?php if (function_exists('kk_star_ratings')): ?>
                <div class="ha-form-booking__kksr">
                    <?php echo kk_star_ratings(); ?>
                </div>
            <?php else: ?>
                <div class="ha-form-booking__stars" aria-label="5 stars rating">
                    <span class="star-icon">★</span>
                    <span class="star-score">4.9</span>
                    <span class="star-count">(<?php esc_html_e('Verified', 'ha'); ?>)</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Booking Inputs Body -->
    <div class="ha-form-booking__body">
        <!-- Date Visit -->
        <div class="form-group ha-form-booking__field">
            <label for="ha-date-visit" class="ha-form-booking__label">
                <?= ha_svg_icon('calendar'); ?>
                <?php esc_html_e('Date of Tour', 'ha'); ?>
            </label>
            <div class="ha-form-booking__input-wrap">
                <input type="date"
                    id="ha-date-visit"
                    name="date_visit"
                    class="form-control ha-form-booking__input"
                    value="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                    min="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                    required>
            </div>
        </div>

        <!-- Guests Selector Row -->
        <div class="form-row ha-form-booking__pax-row">
            <!-- Adults -->
            <div class="form-group half ha-form-booking__pax-col">
                <label class="ha-form-booking__label">
                    <?= ha_svg_icon('user'); ?>
                    <?php esc_html_e('Adults', 'ha'); ?>
                </label>
                <div class="quantity-selector">
                    <button type="button" class="qty-btn qty-btn--minus" aria-label="<?php esc_attr_e('Decrease adults', 'ha'); ?>">-</button>
                    <input type="number" id="ha-adults" name="adults" value="2" min="1" max="50" readonly>
                    <button type="button" class="qty-btn qty-btn--plus" aria-label="<?php esc_attr_e('Increase adults', 'ha'); ?>">+</button>
                </div>
            </div>

            <!-- Children -->
            <div class="form-group half ha-form-booking__pax-col">
                <label class="ha-form-booking__label">
                    <?= ha_svg_icon('users'); ?>
                    <?php esc_html_e('Children', 'ha'); ?>
                    <small class="text-muted">(4-11y)</small>
                </label>
                <div class="quantity-selector">
                    <button type="button" class="qty-btn qty-btn--minus" aria-label="<?php esc_attr_e('Decrease children', 'ha'); ?>">-</button>
                    <input type="number" id="ha-children" name="children" value="0" min="0" max="30" readonly>
                    <button type="button" class="qty-btn qty-btn--plus" aria-label="<?php esc_attr_e('Increase children', 'ha'); ?>">+</button>
                </div>
            </div>
        </div>

        <!-- Form Error Notice -->
        <div class="ha-form-error" role="alert" style="display: none;"></div>

        <!-- Submit / Check Button -->
        <button type="button" id="ha-btn-check-availability" class="ha-button ha-form-booking__submit">
            <span><?php esc_html_e('Check Availability', 'ha'); ?></span>
            <?= ha_svg_icon('arrow-right'); ?>
        </button>
    </div>

    <!-- Trust Features Guarantee -->
    <div class="ha-form-booking__footer">
        <div class="ha-form-booking__trust-item">
            <span class="ha-form-booking__trust-icon">
                <?= ha_svg_icon('shield-check'); ?>
            </span>
            <span class="ha-form-booking__trust-text">
                <?php esc_html_e('Free cancellation up to 24h before tour', 'ha'); ?>
            </span>
        </div>
        <div class="ha-form-booking__trust-item">
            <span class="ha-form-booking__trust-icon">
                <?= ha_svg_icon('check'); ?>
            </span>
            <span class="ha-form-booking__trust-text">
                <?php esc_html_e('Instant confirmation & digital ticket', 'ha'); ?>
            </span>
        </div>
    </div>
</div>
