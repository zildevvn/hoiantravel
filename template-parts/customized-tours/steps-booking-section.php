<?php
$heading = get_field('heading_steps_bk_ctt');
$sub_heading = get_field('sub_heading_steps_bk_ctt');
$steps = get_field('steps_list_ctt');
?>
<section class="vm-section steps-booking-section">
    <div class="steps-booking-section__graphic">
        <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
            alt="image graphic steps booking phu quoc island tours" />
    </div>

    <div class="container">
        <?php vm_icon_heading() ?>
        <?php if (!empty($heading)): ?>
            <h2 class="vm-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>
        <?php if (!empty($sub_heading)): ?>
            <p class="vm-sub-heading">
                <?= $sub_heading ?>
            </p>
        <?php endif; ?>
        <?php if (!empty($steps)): ?>
            <div class="steps-booking-section__list">
                <?php foreach ($steps as $key => $step): ?>
                    <div class="step-item">
                        <div class="step-item__header d-flex align-items-center justify-content-between">
                            <div class="step-item__number h3 d-flex align-items-center justify-content-center"> 0
                                <?= $key + 1 ?>
                            </div>

                            <?php if (!empty($step['icon'])): ?>
                                <div class="step-item__icon d-flex align-items-center justify-content-center">
                                    <img src="<?= $step['icon'] ?>" alt="icon for step <?= $key + 1 ?>" />
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($step['heading'])): ?>
                            <h3 class="h5">
                                <?= $step['heading'] ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($step['description'])): ?>
                            <p class="mb-0">
                                <?= $step['description'] ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>