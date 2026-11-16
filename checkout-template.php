<?php
/**
 * Template Name: Checkout
 */
get_header();

$token = isset($_GET['token']) ? sanitize_text_field(wp_unslash($_GET['token'])) : '';
if (empty($token) && isset($_COOKIE['ha_booking_token'])) {
    $token = sanitize_text_field(wp_unslash($_COOKIE['ha_booking_token']));
}
$booking_data = $token ? get_transient('ha_booking_' . $token) : false;

$error_msg = '';
$tour = null;
$selected_option = null;
$total_price = 0;
$price_per_person = 0;
$total_pax = 0;
$currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

if (!$booking_data || !is_array($booking_data)) {
    $error_msg = 'Your booking session has expired or is invalid. Please return to the tours page and try again.';
} else {
    // Validate again and recalculate
    $tour_id = isset($booking_data['tour_id']) ? intval($booking_data['tour_id']) : 0;
    $option_id = isset($booking_data['option_id']) ? intval($booking_data['option_id']) : 0;
    $tour = get_post($tour_id);

    if (!$tour) {
        $error_msg = 'The selected tour is no longer available.';
    } else {
        $tour_options = get_field('tour_options', $tour_id);

        if (empty($tour_options) || !isset($tour_options[$option_id])) {
            $error_msg = 'The selected tour option is no longer available.';
        } else {
            $selected_option = $tour_options[$option_id];

            // Recalculate price server-side
            $adults = isset($booking_data['adults']) ? max(1, intval($booking_data['adults'])) : 2;
            $children = isset($booking_data['children']) ? max(0, intval($booking_data['children'])) : 0;
            $total_pax = $adults + $children;

            $pricing = ha_calculate_tour_price($selected_option, $adults, $children, $tour_id);
            $price_per_person = $pricing['price_per_person'];
            $total_price = $pricing['total_price'];
            $is_price_available = $pricing['is_price_available'];
        }
    }
}
?>
<main id="primary" class="site-main ha-checkout-page">
    <div class="container" style="padding: 60px 0;">
        <?php if (!empty($error_msg)): ?>
            <div class="ha-checkout-error"
                style="background: #fff3f3; border: 1px solid #ffcaca; padding: 20px; border-radius: 8px; text-align: center; color: #d63031;">
                <p style="margin-bottom: 15px;"><?= esc_html($error_msg) ?></p>
                <a href="<?= site_url('/tours') ?>" class="btn">Return to Tours</a>
            </div>
        <?php else: ?>
            <div class="ha-checkout-wrap">
                <div class="container">
                    <div class="row">
                        <!-- Left Column: Customer Form -->
                        <div class="col-lg-7 col-xl-8">

                            <div id="ha-booking-success" style="display: none;">
                                <div class="success-icon">
                                    <?= ha_svg_icon('check-circle'); ?>
                                </div>
                                <h2>Your order was submitted successfully !</h2>
                                <p class="booking-email-notice">Booking details have been sent to: <b
                                        id="booking-email"></b></p>

                                <div class="booking-confirmation-summary">
                                    <div class="summary-item">
                                        <span class="label">Booking Number</span>
                                        <strong class="value" id="booking-ref"></strong>
                                    </div>
                                    <div class="summary-item">
                                        <span class="label">Booking Date</span>
                                        <strong class="value" id="booking-date"></strong>
                                    </div>
                                    <div class="summary-item">
                                        <span class="label">Payment Method</span>
                                        <strong class="value" id="booking-payment"></strong>
                                    </div>
                                    <div class="summary-item" id="summary-item-country" style="display: none;">
                                        <span class="label">Country</span>
                                        <strong class="value" id="booking-country"></strong>
                                    </div>
                                    <div class="summary-item">
                                        <span class="label">Status</span>
                                        <strong class="value" id="booking-status"></strong>
                                    </div>
                                </div>



                                <div style="margin-top: 32px;">
                                    <a href="<?php echo esc_url(site_url('/tours')); ?>" class="ha-button">
                                        <span><?php esc_html_e('Browse More Tours', 'ha'); ?></span>
                                    </a>
                                </div>
                            </div>

                            <div class="checkout-form-box" id="ha-checkout-form-wrapper">
                                <h2>
                                    <?= ha_svg_icon('user-details'); ?>
                                    Customer Details
                                </h2>

                                <form id="ha-checkout-form" class="ha-form-booking">
                                    <input type="hidden" name="booking_token" value="<?= esc_attr($token) ?>">

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Full Name <span style="color:red;">*</span></label>
                                            <input type="text" name="customer_name" placeholder="Enter your full name">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Email Address <span style="color:red;">*</span></label>
                                            <input type="email" name="customer_email" placeholder="Enter your email">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Phone / WhatsApp <span style="color:red;">*</span></label>
                                            <input type="tel" name="customer_phone" placeholder="Enter phone number">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pick-up Location <span style="color:red;">*</span></label>
                                            <input type="text" name="customer_pickup" placeholder="Hotel name or address">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Drop-off Location <span style="color:red;">*</span></label>
                                            <input type="text" name="customer_dropoff" placeholder="Hotel name or address">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Country</label>
                                            <input type="text" name="customer_country" placeholder="Enter your country">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Special Requests / Messages</label>
                                        <textarea name="customer_messages"
                                            placeholder="Any dietary requirements or special notes?"></textarea>
                                    </div>

                                    <h2 style="margin-top: 40px;">
                                        <?= ha_svg_icon('credit-card'); ?>
                                        Payment Method
                                    </h2>

                                    <div class="form-group">
                                        <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 8px;">
                                            <label
                                                style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 16px; border: 1px solid #e0e0e0; border-radius: 12px; flex: 1; min-width: 200px;">
                                                <input type="radio" name="payment_method" value="pay_on_arrival" checked
                                                    style="width: 20px; height: 20px; margin: 0; accent-color: #C24A2D;">
                                                <strong style="font-weight: 600; color: #19232B;">Pay on Arrival</strong>
                                            </label>
                                            <label
                                                style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 16px; border: 1px solid #e0e0e0; border-radius: 12px; flex: 1; min-width: 200px;">
                                                <input type="radio" name="payment_method" value="bank_transfer"
                                                    style="width: 20px; height: 20px; margin: 0; accent-color: #C24A2D;">
                                                <strong style="font-weight: 600; color: #19232B;">Bank Transfer</strong>
                                            </label>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="form-group" style="margin-top: 24px;">
                                        <label
                                            style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-weight: 400;">
                                            <input type="checkbox" name="terms_conditions"
                                                style="width: 20px; height: 20px; margin-top: 2px; accent-color: #C24A2D; flex-shrink: 0;">
                                            <span>I have read and accept the terms and conditions. <span
                                                    style="color:red;">*</span></span>
                                        </label>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <button type="submit" class="btn-submit-booking ha-button btn-view-license">
                                        CONFIRM BOOKING
                                        <?= ha_svg_icon('arrow-up-right'); ?>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Right Column: Summary -->
                        <div class="col-lg-5 col-xl-4 mt-5 mt-lg-0">
                            <div class="checkout-summary-box ticket-cut-indicator">
                                <div class="summary-header">
                                    <?php if (has_post_thumbnail($tour->ID)): ?>
                                        <?= get_the_post_thumbnail($tour->ID, 'large') ?>
                                    <?php endif; ?>
                                    <h3><?= esc_html($tour->post_title) ?></h3>
                                </div>

                                <ul class="summary-details">
                                    <li>
                                        <?= ha_svg_icon('map-pin-alt'); ?>
                                        <div>
                                            <span>Tour Option</span>
                                            <strong><?= esc_html($selected_option['name']) ?></strong>
                                        </div>
                                    </li>
                                    <li>
                                        <?= ha_svg_icon('calendar'); ?>
                                        <div>
                                            <span>Date & Time</span>
                                            <strong><?= esc_html($booking_data['date']) ?><?= !empty($selected_option['starting_time']) ? ' - ' . esc_html($selected_option['starting_time']) : '' ?></strong>
                                        </div>
                                    </li>
                                    <li>
                                        <?= ha_svg_icon('users'); ?>
                                        <div>
                                            <span>Participants</span>
                                            <strong><?= esc_html($total_pax) ?> (<?= esc_html($booking_data['adults']) ?>
                                                Adults, <?= esc_html($booking_data['children']) ?> Children)</strong>
                                        </div>
                                    </li>
                                </ul>

                                <div class="summary-divider"></div>

                                <div class="summary-pricing">
                                    <h4>Price Breakdown</h4>

                                    <?php if (!$is_price_available): ?>
                                        <div class="price-contact-alert">
                                            <?= ha_svg_icon('alert-triangle'); ?>
                                            <p><strong>Note:</strong> Pricing for this tour requires direct contact. Please
                                                leave your details and we will quote you shortly.</p>
                                        </div>
                                    <?php else: ?>
                                        <ul class="price-list">
                                            <?php if (!empty($pricing['is_car_tour'])): ?>
                                                <li>
                                                    Vehicle Price
                                                    <strong><?= esc_html(ha_format_price($price_per_person, $currency)); ?></strong>
                                                </li>
                                            <?php elseif (!empty($pricing['is_private_tour'])): ?>
                                                <li>
                                                    Private Tour Package
                                                    <strong><?= esc_html(ha_format_price($price_per_person, $currency)); ?></strong>
                                                </li>
                                            <?php else: ?>
                                                <?php if ($adults > 0): ?>
                                                    <li>
                                                        Adults (× <?= esc_html($adults) ?>)
                                                        <strong><?= esc_html(ha_format_price($price_per_person, $currency)); ?></strong>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($children > 0): ?>
                                                    <li>
                                                        Children (× <?= esc_html($children) ?>)
                                                        <strong><?= esc_html(ha_format_price($pricing['child_price'], $currency)); ?></strong>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="total-price-box">
                                            <span>Total Price</span>
                                            <strong class="total-amount"><?= esc_html(ha_format_price($total_price, $currency)); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>