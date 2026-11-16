<?php
/**
 * Single Car Booking Sidebar Form
 *
 * Sticky booking component tailored for Car rentals:
 * 1. Shows "From" price for the car route.
 * 2. Date picker with minimum today.
 * 3. Quantity selectors for Adults and Children.
 * 4. Total passenger counter indicator.
 * 5. Check Availability button triggering car options loading.
 *
 * @package hoiantravel
 */

$price_car = get_field('price_car');
$options = get_field('option_car');
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

if (empty($price_car) && !empty($options) && is_array($options)) {
    $prices = array_filter(array_map(function ($opt) {
        $clean = str_replace(',', '', (string) ($opt['price'] ?? ''));
        return is_numeric($clean) ? floatval($clean) : 0;
    }, $options));
    if (!empty($prices)) {
        $price_car = min($prices);
    }
}
$from_price = $price_car ? $price_car : 35;
?>

<div id="form-booking-car" class="ha-form-booking ha-form-booking--car">
    <!-- Header with Pricing and Rating -->
    <div class="ha-form-booking__header">
        <div class="ha-form-booking__price-box">
            <span class="ha-form-booking__price-label"><?php esc_html_e('From', 'hoiantravel'); ?></span>
            <div class="ha-form-booking__price-wrap">
                <span class="ha-form-booking__price-val">
                    <?= esc_html(ha_format_price($from_price, $currency)); ?>
                </span>
                <span class="ha-form-booking__price-unit">/ <?php esc_html_e('Car', 'hoiantravel'); ?></span>
            </div>
        </div>

        <div class="ha-form-booking__rating-box">
            <?php if (function_exists('kk_star_ratings')): ?>
                <div class="ha-form-booking__kksr">
                    <?php echo kk_star_ratings(); ?>
                </div>
            <?php else: ?>
                <div class="ha-form-booking__stars" aria-label="5 stars rating">
                    <div class="star-row-icons">
                        <span class="star-icon">★</span>
                        <span class="star-icon">★</span>
                        <span class="star-icon">★</span>
                        <span class="star-icon">★</span>
                        <span class="star-icon">★</span>
                    </div>
                    <div class="star-row-legend">
                        <span class="star-score">4.9/5</span>
                        <span class="star-count">(<?php esc_html_e('880 reviews', 'hoiantravel'); ?>)</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Booking Inputs Body -->
    <div class="ha-form-booking__body">
        <!-- Date Visit -->
        <div class="form-group ha-form-booking__field">
            <label for="ha-car-date-visit" class="ha-form-booking__label">
                <?= ha_svg_icon('calendar'); ?>
                <?php esc_html_e('Date of Transfer', 'hoiantravel'); ?>
            </label>
            <div class="ha-form-booking__input-wrap">
                <input type="date" id="ha-car-date-visit" name="car_date_visit"
                    class="form-control ha-form-booking__input" value="<?php echo esc_attr(current_time('Y-m-d')); ?>"
                    min="<?php echo esc_attr(current_time('Y-m-d')); ?>" required>
            </div>
        </div>

        <!-- Passengers Selector Row -->
        <div class="form-row ha-form-booking__pax-row">
            <!-- Adults -->
            <div class="form-group half ha-form-booking__pax-col">
                <label class="ha-form-booking__label">
                    <?= ha_svg_icon('user'); ?>
                    <?php esc_html_e('Adults', 'hoiantravel'); ?>
                </label>
                <div class="quantity-selector ha-car-qty-selector">
                    <button type="button" class="qty-btn qty-btn--minus"
                        aria-label="<?php esc_attr_e('Decrease adults', 'hoiantravel'); ?>">-</button>
                    <input type="number" id="ha-car-adults" name="car_adults" value="2" min="1" max="16" readonly>
                    <button type="button" class="qty-btn qty-btn--plus"
                        aria-label="<?php esc_attr_e('Increase adults', 'hoiantravel'); ?>">+</button>
                </div>
            </div>

            <!-- Children -->
            <div class="form-group half ha-form-booking__pax-col">
                <label class="ha-form-booking__label">
                    <?= ha_svg_icon('users'); ?>
                    <?php esc_html_e('Children', 'hoiantravel'); ?>
                </label>
                <div class="quantity-selector ha-car-qty-selector">
                    <button type="button" class="qty-btn qty-btn--minus"
                        aria-label="<?php esc_attr_e('Decrease children', 'hoiantravel'); ?>">-</button>
                    <input type="number" id="ha-car-children" name="car_children" value="0" min="0" max="15" readonly>
                    <button type="button" class="qty-btn qty-btn--plus"
                        aria-label="<?php esc_attr_e('Increase children', 'hoiantravel'); ?>">+</button>
                </div>
            </div>
        </div>

        <!-- Form Error Notice -->
        <div class="ha-form-error ha-car-form-error" role="alert" style="display: none;"></div>

        <!-- Submit / Check Button -->
        <button type="button" id="ha-btn-check-car-availability" class="ha-button ha-form-booking__submit">
            <span><?php esc_html_e('Check Cars Options', 'hoiantravel'); ?></span>
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
                <?php esc_html_e('Fixed price per vehicle — no hidden surge fees', 'hoiantravel'); ?>
            </span>
        </div>
        <div class="ha-form-booking__trust-item">
            <span class="ha-form-booking__trust-icon">
                <?= ha_svg_icon('check'); ?>
            </span>
            <span class="ha-form-booking__trust-text">
                <?php esc_html_e('Free cancellation up to 24h before pickup', 'hoiantravel'); ?>
            </span>
        </div>
        <div class="ha-form-booking__trust-item">
            <span class="ha-form-booking__trust-icon">
                <?= ha_svg_icon('badge-check-green'); ?>
            </span>
            <span class="ha-form-booking__trust-text">
                <?php esc_html_e('Door-to-door private service with English driver', 'hoiantravel'); ?>
            </span>
        </div>
    </div>
</div>