<?php
$heading = get_field('hd_ab_tpl_ctt');
$sub_hd = get_field('sub_hd_ab_tpl_ctt');
$desc = get_field('description_tpl_ctt');
$gallerys = get_field('gallery_tpl_ctt');

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Customized Phu Quoc Tours',
]);
?>