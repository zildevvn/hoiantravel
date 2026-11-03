<?php
$title = get_the_title();
$image = get_the_post_thumbnail_url();
$desc = get_field('desc_hero_contact_tpl') ?? '';
vm_hero_section_shared($title, $image, $desc);
?>