<?php

// Editor support restored for pages.

// Custom admin columns for tour_booking CPT
add_filter('manage_tour_booking_posts_columns', 'ha_set_custom_edit_tour_booking_columns');
function ha_set_custom_edit_tour_booking_columns($columns)
{
    unset($columns['date']); // Remove default date column, we'll add our own order
    $columns['customer'] = __('Customer', 'ha');
    $columns['tour'] = __('Service', 'ha');
    $columns['booking_date'] = __('Date', 'ha');
    $columns['total_price'] = __('Total Price', 'ha');
    $columns['date'] = __('Submitted On', 'ha');
    return $columns;
}

add_action('manage_tour_booking_posts_custom_column', 'ha_custom_tour_booking_column', 10, 2);
function ha_custom_tour_booking_column($column, $post_id)
{
    switch ($column) {
        case 'customer':
            $name = get_post_meta($post_id, 'customer_name', true);
            $email = get_post_meta($post_id, 'customer_email', true);
            $phone = get_post_meta($post_id, 'customer_phone', true);
            echo "<strong>" . esc_html($name) . "</strong><br>";
            if ($phone)
                echo esc_html($phone) . "<br>";
            if ($email)
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            break;
        case 'tour':
            $tour_id = get_post_meta($post_id, 'tour_id', true);
            $option = get_post_meta($post_id, 'option_name', true);
            $booking_type = get_post_meta($post_id, 'booking_type', true);

            if ($booking_type == 'cars') {
                echo '<span style="background:#00656D;color:#fff;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:0.5px;">Car</span><br>';
            } else {
                // Default to tour if empty for backward compatibility
                echo '<span style="background:#0C2C7A;color:#fff;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:0.5px;">Tour</span><br>';
            }

            if ($tour_id) {
                echo '<a href="' . get_edit_post_link($tour_id) . '" style="display:inline-block; margin-top:6px;"><strong>' . esc_html(get_the_title($tour_id)) . '</strong></a><br>';
            }
            if ($option) {
                echo '<span style="color:#666; font-size:13px;">' . esc_html($option) . '</span>';
            }
            break;
        case 'booking_date':
            echo esc_html(get_post_meta($post_id, 'date', true));
            break;
        case 'total_price':
            $price = get_post_meta($post_id, 'total_price', true);
            if ($price) {
                echo number_format((float) $price, 0, '.', ',') . ' VND';
            }
            break;
    }
}

// Add Custom Meta Box for Booking Details
add_action('add_meta_boxes', 'ha_add_booking_meta_box');
function ha_add_booking_meta_box()
{
    add_meta_box(
        'ha_booking_details',
        __('Booking Details', 'ha'),
        'ha_booking_meta_box_html',
        'tour_booking',
        'normal',
        'high'
    );
}

function ha_booking_meta_box_html($post)
{
    // Get all meta values
    $customer_name = get_post_meta($post->ID, 'customer_name', true);
    $customer_email = get_post_meta($post->ID, 'customer_email', true);
    $customer_phone = get_post_meta($post->ID, 'customer_phone', true);
    $customer_pickup = get_post_meta($post->ID, 'customer_pickup', true);
    $customer_dropoff = get_post_meta($post->ID, 'customer_dropoff', true);
    $customer_address = get_post_meta($post->ID, 'customer_address', true);
    $customer_messages = get_post_meta($post->ID, 'customer_messages', true);
    $payment_method = get_post_meta($post->ID, 'payment_method', true);

    $tour_id = get_post_meta($post->ID, 'tour_id', true);
    $option_name = get_post_meta($post->ID, 'option_name', true);
    $date = get_post_meta($post->ID, 'date', true);
    $starting_time = get_post_meta($post->ID, 'starting_time', true);
    $adults = get_post_meta($post->ID, 'adults', true);
    $children = get_post_meta($post->ID, 'children', true);
    $total_pax = get_post_meta($post->ID, 'total_pax', true);
    $total_price = get_post_meta($post->ID, 'total_price', true);

    // Simple inline CSS for the table
    echo '<style>
        .ha-booking-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .ha-booking-table th { text-align: left; padding: 12px 15px; border: 1px solid #e2e4e7; width: 25%; background: #f8f9fa; font-weight: 600; color: #1d2327; }
        .ha-booking-table td { padding: 12px 15px; border: 1px solid #e2e4e7; color: #3c434a; }
        .ha-booking-section-title { margin-top: 24px; margin-bottom: 12px; font-size: 16px; font-weight: 600; color: #1d2327; }
    </style>';

    echo '<h3 class="ha-booking-section-title">Customer Information</h3>';
    echo '<table class="ha-booking-table"><tbody>';
    echo '<tr><th>Name</th><td>' . esc_html($customer_name) . '</td></tr>';
    echo '<tr><th>Email</th><td><a href="mailto:' . esc_attr($customer_email) . '">' . esc_html($customer_email) . '</a></td></tr>';
    echo '<tr><th>Phone</th><td>' . esc_html($customer_phone) . '</td></tr>';
    echo '<tr><th>Pick-up</th><td>' . esc_html($customer_pickup) . '</td></tr>';
    echo '<tr><th>Drop-off</th><td>' . esc_html($customer_dropoff) . '</td></tr>';
    if ($customer_address)
        echo '<tr><th>Address</th><td>' . esc_html($customer_address) . '</td></tr>';
    if ($customer_messages)
        echo '<tr><th>Messages</th><td>' . nl2br(esc_html($customer_messages)) . '</td></tr>';
    echo '<tr><th>Payment Method</th><td>' . esc_html($payment_method) . '</td></tr>';
    echo '</tbody></table>';

    echo '<h3 class="ha-booking-section-title">Service Details</h3>';
    echo '<table class="ha-booking-table"><tbody>';
    echo '<tr><th>Service Name</th><td><a href="' . get_edit_post_link($tour_id) . '"><strong>' . esc_html(get_the_title($tour_id)) . '</strong></a></td></tr>';
    echo '<tr><th>Option</th><td>' . esc_html($option_name) . '</td></tr>';
    echo '<tr><th>Date</th><td>' . esc_html($date) . '</td></tr>';
    echo '<tr><th>Starting Time</th><td>' . esc_html($starting_time) . '</td></tr>';
    echo '<tr><th>Participants</th><td>' . esc_html($total_pax) . ' (' . esc_html($adults) . ' Adults, ' . esc_html($children) . ' Children)</td></tr>';
    if ($total_price) {
        echo '<tr><th>Total Price</th><td><strong style="color:#0C2C7A;font-size:16px;">' . number_format((float) $total_price, 0, '.', ',') . ' VND</strong></td></tr>';
    } else {
        echo '<tr><th>Total Price</th><td><strong style="color:#0C2C7A;font-size:16px;">Contact for Price</strong></td></tr>';
    }
    echo '</tbody></table>';
}