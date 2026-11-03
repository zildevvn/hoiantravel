<?php
$heading = get_field('hd_car_port_tpl');
$sub_hd = get_field('sub_hd_car_port_tpl');
$form = get_field('form_car_port_tpl');
?>
<section id="book-car-section" class="vm-section book-car-section">
    <div class="container">
        <?php vm_icon_heading() ?>
        <?php if (!empty($heading)): ?>
            <h2 class="vm-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($sub_hd)): ?>
            <p class="vm-sub-heading">
                <?= $sub_hd ?>
            </p>
        <?php endif; ?>

        <div id="book-car-section" class="book-car-section__form">
            <?php echo do_shortcode($form) ?>
        </div>
    </div>
</section>