<?php
$term = get_queried_object();
$about_ss = get_field('about_section', $term);
$heading = $about_ss['heading'] ?? '';
$sub_hd = $about_ss['sub_heading'] ?? '';
$desc = $about_ss['description'] ?? '';
$gallerys = $about_ss['gallerys'] ?? [];

$alt_text = sprintf('%s in Phu Quoc', $term->name ?? 'location');

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => $alt_text,
]);
?>