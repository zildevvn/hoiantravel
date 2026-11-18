<?php
$term = get_queried_object();
$image = get_field('image', $term);
$custom_heading = get_field('custom_heading', $term);
$image_url = $image['url'];
$title = !empty($custom_heading) ? $custom_heading : $term->name;
$alt = get_post_meta($image['id'], '_wp_attachment_image_alt', true);
ha_hero_section_shared($title, $image_url, $term->description, $alt);
?>