<?php
$heading = get_field('heading_sr_hp');
$desc = get_field('desc_sr_hp');
$services = get_field('services_list_hp');
$img_bt = get_field('img_bt_sr_hp');
$img_left = get_field('img_left_sr_hp');
?>


<section class="vm-section services-section">
    <div class="container">
        <div class="services-section-inner d-flex">
            <div class="services-section__left">
                <?php if (!empty($img_left)): ?>
                    <img src="<?= $img_left ?>" alt="image left for serivce  <?= $heading ?> " width="440" height="440" />
                <?php endif; ?>
            </div>

            <div class="services-section__right">
                <?php if (!empty($heading)): ?>
                    <h2 class="mb-0"> <?= $heading ?> </h2>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <p class="mb-0"> <?= $desc ?></p>
                <?php endif; ?>

                <?php if (!empty($services)): ?>
                    <div class="services-section__list">
                        <?php foreach ($services as $key => $service): ?>
                            <div class="service-item">
                                <?php if (!empty($service['icon'])): ?>
                                    <div class="service-item__icon">
                                        <img src="<?= $service['icon'] ?>" alt="icon for serivce  <?= $service['headin'] ?>"
                                            width="150" height="100" />
                                    </div>
                                <?php endif; ?>

                                <div class="service-item-content">
                                    <?php if (!empty($service['headin'])): ?>
                                        <h3 class="h5">
                                            <?= $service['headin'] ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if (!empty($service['desc'])): ?>
                                        <p class="mb-0">
                                            <?= $service['desc'] ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="service-item__step">
                                    <div class="step-corner">
                                        <span></span>
                                        <p class="number h4">0<?= $key + 1 ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($img_bt)): ?>
                    <div class="services-section__img-bt">
                        <img src="<?= $img_bt ?>" alt="image for serivce  <?= $heading ?> " />
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>