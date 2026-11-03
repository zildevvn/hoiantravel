<?php
$heading = get_field('hd_ab_tpl_tour');
$sub_hd = get_field('sub_hd_ab_tpl_tour');
$desc = get_field('desc_ab_tpl_tour');
$gallerys = get_field('gallery_ab_tpl_tour');

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Phu Quoc Tours',
]);
?>