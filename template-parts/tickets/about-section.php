<?php
$heading = get_field('hd_ab_ticket_tpl');
$sub_hd = get_field('sub_hd_ab_ticket_tpl');
$desc = get_field('description_ticket_tpl');
$gallerys = get_field('gallery_ticket_tpl');

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Phu Quoc Tickets',
]);
?>