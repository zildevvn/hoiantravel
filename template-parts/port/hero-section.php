<?php
/**
 * Port Template - Hero Section
 *
 * Fully synchronized with Customized Tours hero section and Hoi An Travel Design System.
 *
 * @package hoiantravel
 */

$title    = get_the_title();
$image    = get_the_post_thumbnail_url();
$image_id = get_post_thumbnail_id();
$desc     = get_the_content() ?? '';
$alt      = get_post_meta($image_id, '_wp_attachment_image_alt', true);

ha_hero_section_shared($title, $image, $desc, $alt);
?>
