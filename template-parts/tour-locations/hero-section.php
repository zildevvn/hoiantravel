<?php
$term = get_queried_object();
$image = get_field('image', $term);
$image_url = $image['url'];
$title = $term->name;
vm_hero_section_shared($title, $image_url, $term->description);
?>