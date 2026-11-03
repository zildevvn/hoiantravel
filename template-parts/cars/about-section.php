<?php
$heading = get_field('hd_ab_car_tpl');
$sub_hd = get_field('sub_hd_ab_car_tpl');
$desc = get_field('description_car_tpl');
$gallerys = get_field('gallery_car_tpl');

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Car Rental in Phu Quoc',
]);
?>