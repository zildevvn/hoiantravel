<?php
/**
 * AJAX Handlers for Hoi An Travel Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_ha_ajax_filter_tours', 'ha_ajax_filter_tours_handler');
add_action('wp_ajax_nopriv_ha_ajax_filter_tours', 'ha_ajax_filter_tours_handler');

function ha_ajax_filter_tours_handler()
{
    check_ajax_referer('ha_filter_tours', 'nonce');

    $s = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';
    $tour_cat = isset($_POST['tour_cat']) ? sanitize_text_field(wp_unslash($_POST['tour_cat'])) : 'all';
    $pax_min = isset($_POST['pax_min']) ? intval($_POST['pax_min']) : 1;
    $pax_max = isset($_POST['pax_max']) ? intval($_POST['pax_max']) : 50;
    $price_min = isset($_POST['price_min']) ? max(0, min(500, floatval($_POST['price_min']))) : 0;
    $price_max = isset($_POST['price_max']) ? max(0, min(500, floatval($_POST['price_max']))) : 500;
    $sort = isset($_POST['sort']) ? sanitize_text_field(wp_unslash($_POST['sort'])) : 'default';
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

    $args = array(
        'post_type'      => 'tours',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
    );

    // Search query
    if (!empty($s)) {
        $args['s'] = $s;
    }

    // Taxonomy filter
    if (!empty($tour_cat) && $tour_cat !== 'all') {
        if (is_numeric($tour_cat)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'tour_cats',
                    'field'    => 'term_id',
                    'terms'    => intval($tour_cat),
                ),
            );
        } else {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'tour_cats',
                    'field'    => 'slug',
                    'terms'    => $tour_cat,
                ),
            );
        }
    }

    // Meta query for price
    $meta_query = array('relation' => 'AND');

    if ($price_max >= 0 && $price_max >= $price_min) {
        $meta_query[] = array(
            'key'     => 'price_tour',
            'value'   => array($price_min, $price_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    // Sorting
    switch ($sort) {
        case 'price_low':
            $args['meta_key'] = 'price_tour';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price_high':
            $args['meta_key'] = 'price_tour';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'newest':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
        case 'title_az':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;
        default:
            $args['orderby'] = 'menu_order date';
            $args['order']   = 'DESC';
            break;
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ha_item_tour();
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    ob_start();
    ha_pagination($paged, $query->max_num_pages);
    $pagination = ob_get_clean();

    wp_send_json_success(array(
        'html'       => $html,
        'pagination' => $pagination,
        'count'      => $query->found_posts,
        'max_pages'  => $query->max_num_pages,
    ));
}

add_action('wp_ajax_ha_ajax_filter_cars', 'ha_ajax_filter_cars_handler');
add_action('wp_ajax_nopriv_ha_ajax_filter_cars', 'ha_ajax_filter_cars_handler');

function ha_ajax_filter_cars_handler()
{
    check_ajax_referer('ha_filter_cars', 'nonce');

    $s = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';
    $car_cat = isset($_POST['car_cat']) ? sanitize_text_field(wp_unslash($_POST['car_cat'])) : (isset($_POST['tour_cat']) ? sanitize_text_field(wp_unslash($_POST['tour_cat'])) : 'all');
    $pax_min = isset($_POST['pax_min']) ? intval($_POST['pax_min']) : 1;
    $pax_max = isset($_POST['pax_max']) ? intval($_POST['pax_max']) : 50;
    $price_min = isset($_POST['price_min']) ? max(0, min(500, floatval($_POST['price_min']))) : 0;
    $price_max = isset($_POST['price_max']) ? max(0, min(500, floatval($_POST['price_max']))) : 500;
    $sort = isset($_POST['sort']) ? sanitize_text_field(wp_unslash($_POST['sort'])) : 'default';
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

    $args = array(
        'post_type'      => 'cars',
        'post_status'    => 'publish',
        'posts_per_page' => 9,
        'paged'          => $paged,
    );

    // Search query
    if (!empty($s)) {
        $args['s'] = $s;
    }

    // Taxonomy filter (car_category or fallback to tour_cats)
    if (!empty($car_cat) && $car_cat !== 'all') {
        $tax_to_use = taxonomy_exists('car_category') ? 'car_category' : 'tour_cats';
        if ($tax_to_use === 'car_category') {
            $term_check = is_numeric($car_cat) ? get_term(intval($car_cat), 'car_category') : get_term_by('slug', $car_cat, 'car_category');
            if (!$term_check || is_wp_error($term_check)) {
                $tax_to_use = 'tour_cats';
            }
        }

        $args['tax_query'] = array(
            array(
                'taxonomy' => $tax_to_use,
                'field'    => is_numeric($car_cat) ? 'term_id' : 'slug',
                'terms'    => is_numeric($car_cat) ? intval($car_cat) : $car_cat,
            ),
        );
    }

    // Meta query for price
    $meta_query = array('relation' => 'AND');

    if ($price_max >= 0 && $price_max >= $price_min) {
        $meta_query[] = array(
            'key'     => 'price_car',
            'value'   => array($price_min, $price_max),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    if (count($meta_query) > 1) {
        $args['meta_query'] = $meta_query;
    }

    // Sorting
    switch ($sort) {
        case 'price_low':
            $args['meta_key'] = 'price_car';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;
        case 'price_high':
            $args['meta_key'] = 'price_car';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;
        case 'newest':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
        case 'title_az':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;
        default:
            $args['orderby'] = 'menu_order date';
            $args['order']   = 'DESC';
            break;
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ha_item_car();
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    ob_start();
    ha_pagination($paged, $query->max_num_pages);
    $pagination = ob_get_clean();

    wp_send_json_success(array(
        'html'       => $html,
        'pagination' => $pagination,
        'count'      => $query->found_posts,
        'max_pages'  => $query->max_num_pages,
    ));
}

add_action('wp_ajax_ha_ajax_filter_posts', 'ha_ajax_filter_posts_handler');
add_action('wp_ajax_nopriv_ha_ajax_filter_posts', 'ha_ajax_filter_posts_handler');

function ha_ajax_filter_posts_handler()
{
    check_ajax_referer('ha_filter_posts', 'nonce');

    $s        = isset($_POST['s']) ? sanitize_text_field(wp_unslash($_POST['s'])) : '';
    $category = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : (isset($_POST['post_cat']) ? sanitize_text_field(wp_unslash($_POST['post_cat'])) : 'all');
    $paged    = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;

    $args = array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 12,
        'paged'          => $paged,
    );

    // Search query
    if (!empty($s)) {
        $args['s'] = $s;
    }

    // Taxonomy filter
    $selected_term = null;
    if (!empty($category) && $category !== 'all') {
        if (is_numeric($category)) {
            $selected_term = get_term(intval($category), 'category');
        } else {
            $selected_term = get_term_by('slug', $category, 'category');
        }

        if ($selected_term && !is_wp_error($selected_term)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $selected_term->term_id,
                ),
            );
        }
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ha_item_post();
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();

    ob_start();
    $pagination_args = [];
    if (!empty($s)) {
        $pagination_args['s'] = $s;
    }
    if ($selected_term) {
        $pagination_args['category'] = $selected_term->slug;
    }
    ha_pagination($paged, $query->max_num_pages, $pagination_args);
    $pagination = ob_get_clean();

    wp_send_json_success(array(
        'html'          => $html,
        'pagination'    => $pagination,
        'count'         => $query->found_posts,
        'max_pages'     => $query->max_num_pages,
        'category_name' => $selected_term ? $selected_term->name : '',
    ));
}

/**
 * AJAX: Check Tour Options Availability
 */
add_action('wp_ajax_ha_ajax_check_availability', 'ha_ajax_check_availability_handler');
add_action('wp_ajax_nopriv_ha_ajax_check_availability', 'ha_ajax_check_availability_handler');
function ha_ajax_check_availability_handler()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed. Please refresh the page and try again.', 'ha')));
    }

    $post_id  = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $date     = isset($_POST['date']) ? sanitize_text_field(wp_unslash($_POST['date'])) : '';
    $adults   = isset($_POST['adults']) ? intval($_POST['adults']) : 2;
    $children = isset($_POST['children']) ? intval($_POST['children']) : 0;

    if (!$post_id) {
        wp_send_json_error(array('message' => __('Invalid tour.', 'ha')));
    }

    if (!empty($date) && strtotime($date) < strtotime(current_time('Y-m-d'))) {
        wp_send_json_error(array('message' => __('Please select today or a future date.', 'ha')));
    }

    if ($adults < 0 || $children < 0) {
        wp_send_json_error(array('message' => __('Invalid participant count.', 'ha')));
    }

    $total_pax = $adults + $children;
    if ($total_pax < 1 || $total_pax > 50) {
        wp_send_json_error(array('message' => __('Total number of guests must be between 1 and 50.', 'ha')));
    }

    global $post;
    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(array('message' => __('Tour not found.', 'ha')));
    }
    setup_postdata($post);

    $tour_options = get_field('tour_options', $post_id);

    if (empty($tour_options)) {
        wp_reset_postdata();
        wp_send_json_success(array(
            'html'  => '',
            'count' => 0,
        ));
    }

    ob_start();
    get_template_part('template-parts/single-tour/tour-options');
    $html = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array(
        'html'  => $html,
        'count' => count($tour_options),
    ));
}

/**
 * AJAX: Process Tour Booking Session
 */
add_action('wp_ajax_ha_ajax_process_booking', 'ha_ajax_process_booking_handler');
add_action('wp_ajax_nopriv_ha_ajax_process_booking', 'ha_ajax_process_booking_handler');
function ha_ajax_process_booking_handler()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed. Please refresh the page and try again.', 'ha')));
    }

    $post_id   = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $option_id = isset($_POST['option_id']) ? intval($_POST['option_id']) : -1;
    $date      = isset($_POST['date']) ? sanitize_text_field(wp_unslash($_POST['date'])) : '';
    $adults    = isset($_POST['adults']) ? intval($_POST['adults']) : 2;
    $children  = isset($_POST['children']) ? intval($_POST['children']) : 0;

    if (!$post_id || $option_id < 0 || empty($date)) {
        wp_send_json_error(array('message' => __('Invalid booking details.', 'ha')));
    }

    if (strtotime($date) < strtotime(current_time('Y-m-d'))) {
        wp_send_json_error(array('message' => __('Please select today or a future date.', 'ha')));
    }

    if ($adults < 0 || $children < 0) {
        wp_send_json_error(array('message' => __('Invalid participant count.', 'ha')));
    }

    $total_pax = $adults + $children;
    if ($total_pax < 1 || $total_pax > 50) {
        wp_send_json_error(array('message' => __('Total number of guests must be between 1 and 50.', 'ha')));
    }

    $post = get_post($post_id);
    if (!$post) {
        wp_send_json_error(array('message' => __('Tour not found.', 'ha')));
    }

    $tour_options = get_field('tour_options', $post_id);
    if (empty($tour_options) || !isset($tour_options[$option_id])) {
        wp_send_json_error(array('message' => __('Selected tour option is not available.', 'ha')));
    }

    $selected_option = $tour_options[$option_id];
    $token = bin2hex(random_bytes(16));
    $booking_data = array(
        'tour_id'     => $post_id,
        'option_id'   => $option_id,
        'option_name' => $selected_option['name'] ?? '',
        'date'        => $date,
        'date_visit'  => $date,
        'adults'      => $adults,
        'children'    => $children,
        'created_at'  => current_time('mysql'),
    );

    set_transient('ha_booking_' . $token, $booking_data, HOUR_IN_SECONDS);

    // Backup token in cookie for resilient recovery
    if (!headers_sent()) {
        $cookie_path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
        $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
        setcookie('ha_booking_token', $token, time() + HOUR_IN_SECONDS, $cookie_path, $cookie_domain, is_ssl(), false);
    }

    $checkout_url = home_url('/checkout/');
    $checkout_pages = get_pages(array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'checkout-template.php',
        'number'     => 1,
    ));
    if (empty($checkout_pages)) {
        $page_by_path = get_page_by_path('checkout');
        if ($page_by_path) {
            $checkout_pages = array($page_by_path);
        }
    }
    if (!empty($checkout_pages)) {
        $checkout_url = get_permalink($checkout_pages[0]->ID);
    }

    $checkout_url = trailingslashit($checkout_url);

    wp_send_json_success(array(
        'redirect_url' => add_query_arg('token', $token, $checkout_url),
        'token'        => $token,
    ));
}

/**
 * AJAX: Submit Tour Checkout Booking
 */
add_action('wp_ajax_ha_ajax_submit_checkout', 'ha_ajax_submit_checkout_handler');
add_action('wp_ajax_nopriv_ha_ajax_submit_checkout', 'ha_ajax_submit_checkout_handler');
function ha_ajax_submit_checkout_handler()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'tour_options_nonce')) {
        wp_send_json_error(array('message' => __('Security check failed. Please refresh the page and try again.', 'ha')));
    }

    $token = isset($_POST['booking_token']) ? sanitize_text_field(wp_unslash($_POST['booking_token'])) : '';
    if (empty($token) && isset($_COOKIE['ha_booking_token'])) {
        $token = sanitize_text_field(wp_unslash($_COOKIE['ha_booking_token']));
    }

    if (empty($token)) {
        wp_send_json_error(array('message' => __('Invalid booking session. Please return to the tour page and select your option again.', 'ha')));
    }

    $booking_data = get_transient('ha_booking_' . $token);
    if (!$booking_data || !is_array($booking_data)) {
        wp_send_json_error(array('message' => __('Your booking session has expired or is invalid. Please return to the tour page and try again.', 'ha')));
    }

    // Extract and sanitize customer info
    $customer_name     = isset($_POST['customer_name']) ? sanitize_text_field(wp_unslash($_POST['customer_name'])) : '';
    $customer_email    = isset($_POST['customer_email']) ? sanitize_email(wp_unslash($_POST['customer_email'])) : '';
    $customer_phone    = isset($_POST['customer_phone']) ? sanitize_text_field(wp_unslash($_POST['customer_phone'])) : '';
    $customer_pickup   = isset($_POST['customer_pickup']) ? sanitize_text_field(wp_unslash($_POST['customer_pickup'])) : '';
    $customer_dropoff  = isset($_POST['customer_dropoff']) ? sanitize_text_field(wp_unslash($_POST['customer_dropoff'])) : '';
    $customer_country  = isset($_POST['customer_country']) ? sanitize_text_field(wp_unslash($_POST['customer_country'])) : '';
    $customer_messages = isset($_POST['customer_messages']) ? sanitize_textarea_field(wp_unslash($_POST['customer_messages'])) : '';
    $payment_method    = isset($_POST['payment_method']) ? sanitize_text_field(wp_unslash($_POST['payment_method'])) : 'pay_on_arrival';

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_pickup) || empty($customer_dropoff)) {
        wp_send_json_error(array('message' => __('Please fill in all required fields.', 'ha')));
    }

    if (!is_email($customer_email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'ha')));
    }

    // Re-validate tour & option details from DB
    $tour_id   = isset($booking_data['tour_id']) ? intval($booking_data['tour_id']) : 0;
    $option_id = isset($booking_data['option_id']) ? intval($booking_data['option_id']) : 0;
    $tour      = get_post($tour_id);

    if (!$tour) {
        wp_send_json_error(array('message' => __('The selected tour is no longer available.', 'ha')));
    }

    $tour_options = get_field('tour_options', $tour_id);
    if (empty($tour_options) || !isset($tour_options[$option_id])) {
        wp_send_json_error(array('message' => __('The selected tour option is no longer available.', 'ha')));
    }

    $selected_option = $tour_options[$option_id];

    // Recalculate price server-side
    $adults    = isset($booking_data['adults']) ? max(1, intval($booking_data['adults'])) : 2;
    $children  = isset($booking_data['children']) ? max(0, intval($booking_data['children'])) : 0;
    $total_pax = $adults + $children;

    $pricing            = ha_calculate_tour_price($selected_option, $adults, $children, $tour_id);
    $price_per_person   = $pricing['price_per_person'];
    $total_price        = $pricing['total_price'];
    $is_price_available = $pricing['is_price_available'];

    // Prevent duplicate booking submission server-side
    $existing_booking = get_posts(array(
        'post_type'      => 'tour_booking',
        'meta_key'       => 'booking_token',
        'meta_value'     => $token,
        'post_status'    => 'any',
        'fields'         => 'ids',
        'posts_per_page' => 1,
    ));

    if (!empty($existing_booking)) {
        delete_transient('ha_booking_' . $token);
        wp_send_json_error(array('message' => __('This booking has already been processed successfully.', 'ha')));
    }

    // Generate Booking Reference
    $booking_ref = 'HA-' . strtoupper(substr(uniqid(), -6));

    // Save Booking to Database (Custom Post Type)
    $booking_post_id = wp_insert_post(array(
        'post_title'  => $booking_ref,
        'post_status' => 'publish',
        'post_type'   => 'tour_booking',
    ), true);

    if (is_wp_error($booking_post_id) || empty($booking_post_id)) {
        wp_send_json_error(array('message' => __('Failed to save booking. Please try again or contact support.', 'ha')));
    }

    // Customer Info Meta
    update_post_meta($booking_post_id, 'customer_name', $customer_name);
    update_post_meta($booking_post_id, 'customer_email', $customer_email);
    update_post_meta($booking_post_id, 'customer_phone', $customer_phone);
    update_post_meta($booking_post_id, 'customer_pickup', $customer_pickup);
    update_post_meta($booking_post_id, 'customer_dropoff', $customer_dropoff);
    update_post_meta($booking_post_id, 'customer_country', $customer_country);
    update_post_meta($booking_post_id, 'customer_address', $customer_country);
    update_post_meta($booking_post_id, 'customer_messages', $customer_messages);
    update_post_meta($booking_post_id, 'payment_method', $payment_method);

    // Tour Info Meta
    $booking_type = get_post_type($tour_id) ?: 'tours';
    update_post_meta($booking_post_id, 'booking_type', $booking_type);
    update_post_meta($booking_post_id, 'tour_id', $tour_id);
    update_post_meta($booking_post_id, 'booking_token', $token);
    update_post_meta($booking_post_id, 'option_name', $selected_option['name'] ?? '');
    update_post_meta($booking_post_id, 'date', $booking_data['date']);
    update_post_meta($booking_post_id, 'starting_time', $selected_option['starting_time'] ?? '');
    update_post_meta($booking_post_id, 'adults', $adults);
    update_post_meta($booking_post_id, 'children', $children);
    update_post_meta($booking_post_id, 'total_pax', $total_pax);
    update_post_meta($booking_post_id, 'price_per_person', $price_per_person);
    update_post_meta($booking_post_id, 'total_price', $total_price);

    $payment_method_label = $payment_method === 'bank_transfer' ? 'Bank Transfer' : 'Pay on Arrival';
    $formatted_date       = date('F j, Y', strtotime($booking_data['date']));
    $formatted_price      = ha_format_price($total_price);

    // HTML Email Styling
    $table_style = 'width: 100%; max-width: 600px; border-collapse: collapse; margin-bottom: 20px; font-family: Arial, sans-serif; font-size: 14px;';
    $th_style    = 'padding: 12px; border: 1px solid #e7e5e4; background-color: #FAF8F5; text-align: left; font-weight: bold; width: 35%; color: #19232B;';
    $td_style    = 'padding: 12px; border: 1px solid #e7e5e4; color: #555; text-align: left;';
    $h2_style    = 'color: #C24A2D; font-family: Arial, sans-serif; font-size: 24px; border-bottom: 2px solid #DFA128; padding-bottom: 10px; margin-bottom: 20px;';
    $h3_style    = 'color: #19232B; font-family: Arial, sans-serif; font-size: 18px; margin-top: 30px; margin-bottom: 15px;';

    // 1. Admin Email
    $admin_email   = get_field('booking_notification_email', 'option') ?: get_option('admin_email');
    $subject_admin = 'New Booking Request: ' . $booking_ref;

    $message_admin  = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>";
    $message_admin .= "<h2 style='{$h2_style}'>New Booking Request: {$booking_ref}</h2>";
    $message_admin .= "<h3 style='{$h3_style}'>Customer Information</h3>";
    $message_admin .= "<table style='{$table_style}'>";
    $message_admin .= "<tr><th style='{$th_style}'>Name</th><td style='{$td_style}'>" . esc_html($customer_name) . "</td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Email</th><td style='{$td_style}'><a href='mailto:" . esc_attr($customer_email) . "'>" . esc_html($customer_email) . "</a></td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Phone</th><td style='{$td_style}'>" . esc_html($customer_phone) . "</td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Pick-up</th><td style='{$td_style}'>" . esc_html($customer_pickup) . "</td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Drop-off</th><td style='{$td_style}'>" . esc_html($customer_dropoff) . "</td></tr>";
    if (!empty($customer_country)) {
        $message_admin .= "<tr><th style='{$th_style}'>Country</th><td style='{$td_style}'>" . esc_html($customer_country) . "</td></tr>";
    }
    if (!empty($customer_messages)) {
        $message_admin .= "<tr><th style='{$th_style}'>Messages</th><td style='{$td_style}'>" . nl2br(esc_html($customer_messages)) . "</td></tr>";
    }
    $message_admin .= "<tr><th style='{$th_style}'>Payment Method</th><td style='{$td_style}'>" . esc_html($payment_method_label) . "</td></tr>";
    $message_admin .= "</table>";

    $message_admin .= "<h3 style='{$h3_style}'>Booking Information</h3>";
    $message_admin .= "<table style='{$table_style}'>";
    $message_admin .= "<tr><th style='{$th_style}'>Tour</th><td style='{$td_style}'><strong>" . esc_html($tour->post_title) . "</strong></td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Option</th><td style='{$td_style}'>" . esc_html($selected_option['name'] ?? '') . "</td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Date</th><td style='{$td_style}'>" . esc_html($formatted_date) . "</td></tr>";
    if (!empty($selected_option['starting_time'])) {
        $message_admin .= "<tr><th style='{$th_style}'>Starting Time</th><td style='{$td_style}'>" . esc_html($selected_option['starting_time']) . "</td></tr>";
    }
    $message_admin .= "<tr><th style='{$th_style}'>Participants</th><td style='{$td_style}'>{$total_pax} ({$adults} Adults, {$children} Children)</td></tr>";
    $message_admin .= "<tr><th style='{$th_style}'>Total Price</th><td style='{$td_style}'><strong style='color: #C24A2D; font-size: 16px;'>" . esc_html($formatted_price) . "</strong></td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "</div>";

    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($admin_email, $subject_admin, $message_admin, $headers);

    // 2. Customer Confirmation Email
    if (is_email($customer_email)) {
        $subject_customer = 'Booking Confirmation: ' . $booking_ref . ' - Hoi An Travel';

        $message_customer  = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>";
        $message_customer .= "<h2 style='{$h2_style}'>Booking Confirmation: {$booking_ref}</h2>";
        $message_customer .= "<p style='font-size: 15px; line-height: 1.6; color: #555;'>Dear <strong>" . esc_html($customer_name) . "</strong>,</p>";
        $message_customer .= "<p style='font-size: 15px; line-height: 1.6; color: #555;'>Thank you for your booking request! We have received your booking details and our team will contact you shortly to confirm your reservation.</p>";

        $message_customer .= "<h3 style='{$h3_style}'>Your Booking Summary</h3>";
        $message_customer .= "<table style='{$table_style}'>";
        $message_customer .= "<tr><th style='{$th_style}'>Tour</th><td style='{$td_style}'><strong>" . esc_html($tour->post_title) . "</strong></td></tr>";
        $message_customer .= "<tr><th style='{$th_style}'>Option</th><td style='{$td_style}'>" . esc_html($selected_option['name'] ?? '') . "</td></tr>";
        $message_customer .= "<tr><th style='{$th_style}'>Date</th><td style='{$td_style}'>" . esc_html($formatted_date) . "</td></tr>";
        if (!empty($selected_option['starting_time'])) {
            $message_customer .= "<tr><th style='{$th_style}'>Starting Time</th><td style='{$td_style}'>" . esc_html($selected_option['starting_time']) . "</td></tr>";
        }
        $message_customer .= "<tr><th style='{$th_style}'>Participants</th><td style='{$td_style}'>{$total_pax} ({$adults} Adults, {$children} Children)</td></tr>";
        $message_customer .= "<tr><th style='{$th_style}'>Pick-up Location</th><td style='{$td_style}'>" . esc_html($customer_pickup) . "</td></tr>";
        $message_customer .= "<tr><th style='{$th_style}'>Drop-off Location</th><td style='{$td_style}'>" . esc_html($customer_dropoff) . "</td></tr>";
        if (!empty($customer_country)) {
            $message_customer .= "<tr><th style='{$th_style}'>Country</th><td style='{$td_style}'>" . esc_html($customer_country) . "</td></tr>";
        }
        $message_customer .= "<tr><th style='{$th_style}'>Total Price</th><td style='{$td_style}'><strong style='color: #C24A2D; font-size: 16px;'>" . esc_html($formatted_price) . "</strong></td></tr>";
        $message_customer .= "<tr><th style='{$th_style}'>Payment Method</th><td style='{$td_style}'>" . esc_html($payment_method_label) . "</td></tr>";
        $message_customer .= "</table>";

        $message_customer .= "<p style='font-size: 14px; color: #888; margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;'>If you have any questions or wish to make changes, please reply directly to this email or reach us via WhatsApp.</p>";
        $message_customer .= "</div>";

        wp_mail($customer_email, $subject_customer, $message_customer, $headers);
    }

    // Clean up session transient & cookie
    delete_transient('ha_booking_' . $token);
    if (!headers_sent()) {
        $cookie_path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
        $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
        setcookie('ha_booking_token', '', time() - 3600, $cookie_path, $cookie_domain, is_ssl(), false);
    }

    wp_send_json_success(array(
        'message'        => __('Booking submitted successfully.', 'ha'),
        'reference'      => $booking_ref,
        'date'           => $formatted_date,
        'payment_method' => $payment_method_label,
        'status'         => __('Pending Confirmation', 'ha'),
        'country'        => $customer_country,
    ));
}

