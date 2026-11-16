<?php
$heading = get_field('hd_ab_port_tpl');
$sub_hd = get_field('sub_hd_ab_port_tpl');
$desc = get_field('description_port_tpl');
$gallerys = get_field('gallery_port_tpl');

ob_start();
?>
<div class="d-flex align-items-center flex-wrap gap-2 mt-4">
    <a href="#daily-tours-section" class="vm-button d-flex align-items-center ">
        Daily Tours
        <?= ha_svg_icon('arrow-up-right'); ?>
    </a>

    <a href="#book-car-section" class="vm-button vm-button-secondary d-flex align-items-center ">
        Book Car
        <?= ha_svg_icon('arrow-up-right'); ?>
    </a>
</div>
<?php
$append_content = ob_get_clean();

get_template_part('template-parts/shared/about-section', null, [
    'heading' => $heading,
    'sub_heading' => $sub_hd,
    'description' => $desc,
    'gallery' => $gallerys,
    'alt_text' => 'Phu Quoc ports',
    'append_content' => $append_content,
]);
?>