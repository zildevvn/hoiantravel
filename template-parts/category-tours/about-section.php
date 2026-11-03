<?php
$heading = get_field('hd_ab_cate_tours');
$sub_hd = get_field('sub_hd_ab_cate_tours');
$desc = get_field('desc_ab_cate_tours');
$gallerys = get_field('gallerys_ab_cate_tours');
$counters = get_field('counters_ab_cate_tours');

ob_start();
?>
<div class="about-section__graphic">
    <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
        alt="image graphic Phu Quoc Day Trip" />
</div>

<?php
$prepend_section = ob_get_clean();

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Phu Quoc Day Trip',
    'prepend_section' => $prepend_section,
    'counters' => $counters
]);
?>