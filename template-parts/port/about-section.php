<?php
/**
 * Port Template - About / Media Showcase Section
 *
 * Fully synchronized with Customized Tours About section and shared media showcase component.
 * Pulls ACF fields for Port and passes them to the shared clover collage layout.
 *
 * @package hoiantravel
 */

$heading  = get_field('hd_ab_port_tpl');
$sub_hd   = get_field('sub_hd_ab_port_tpl');
$desc     = get_field('description_port_tpl');
$gallerys = get_field('gallery_port_tpl');

// Intelligent contextual ALT text fallback
$alt_text = !empty($heading) ? strip_tags($heading) : __('Hoi An Port Transfers & Shore Excursions', 'hoiantravel');

get_template_part('template-parts/shared/about-section', null, [
    'heading'     => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery'     => $gallerys,
    'alt_text'    => $alt_text,
]);
?>
