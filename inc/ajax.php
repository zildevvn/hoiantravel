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
    $price_min = isset($_POST['price_min']) ? floatval($_POST['price_min']) : 0;
    $price_max = isset($_POST['price_max']) ? floatval($_POST['price_max']) : 20000000;
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

    if ($price_max > 0) {
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

