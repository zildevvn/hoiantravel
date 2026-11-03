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
        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            color="#000000">
            <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </a>

    <a href="#book-car-section" class="vm-button vm-button-secondary d-flex align-items-center ">
        Book Car
        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            color="#000000">
            <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
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