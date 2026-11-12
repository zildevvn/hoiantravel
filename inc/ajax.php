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

