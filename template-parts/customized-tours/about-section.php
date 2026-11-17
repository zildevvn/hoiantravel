<?php
/**
 * Customized Tours Template - About / Media Showcase Section
 *
 * Pulls ACF fields for Customized Tours and passes them to the shared media carousel component.
 *
 * @package hoiantravel
 */

$heading   = get_field('hd_ab_tpl_ctt');
$sub_hd    = get_field('sub_hd_ab_tpl_ctt');
$desc      = get_field('description_tpl_ctt');
$gallerys  = get_field('gallery_tpl_ctt');

// Intelligent contextual ALT text fallback
$alt_text  = !empty($heading) ? strip_tags($heading) : __('Customized Hoi An Tours & Private Journeys', 'hoiantravel');

get_template_part('template-parts/shared/about-section', null, [
    'heading'     => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery'     => $gallerys,
    'alt_text'    => $alt_text,
]);
