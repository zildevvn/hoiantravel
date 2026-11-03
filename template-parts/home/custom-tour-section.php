<?php
$heading = get_field('hd_custom_tour_hp');
$desc = get_field('desc_custom_tour_hp');
$bg = get_field('bg_custom_tour_hp');
$services = get_field('services_custom_tour_hp');
?>
<section class="vm-section custom-tour-section">
    <div class="container">
        <div class="custom-tour-section__warp">
            <?php if ($bg): ?>
                <div class="vm-section__bg">
                    <img src="<?= $bg ?>" alt="image for custom tour Phu Quoc Island Holiday Packages" />
                </div>
            <?php endif; ?>

            <div class="custom-tour-section-inner d-flex justify-content-between align-items-center">
                <div class="custom-tour-section__left">
                    <div class="custom-tour-section__lable"> Custom Tour </div>

                    <?php if (!empty($heading)): ?>
                        <h2 class="vm-heading h3">
                            <?= $heading ?>
                        </h2>
                    <?php endif; ?>

                    <?php if (!empty($desc)): ?>
                        <div class="custom-tour-section__desc">
                            <?= $desc ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($services)): ?>
                        <div class="custom-tour-section__services d-flex align-items-center">
                            <?php foreach ($services as $key => $service): ?>
                                <div class="service-item d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-check-check preview-icon">
                                        <path d="M18 6 7 17l-5-5" />
                                        <path d="m22 10-7.5 7.5L13 16" />
                                    </svg>
                                    <?= $service['service_item'] ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($services)): ?>
                    <div class="custom-tour-section__right">
                        <button id="custom-tour-btn" class="vm-button d-flex align-items-center justify-content-center">
                            Request a Custom Tour
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="#000000">
                                <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>

                        <?php vm_load_button('https://wa.me/84705243243', 'Chat WhatsApp!', '_blank', 'View All car rental Tours') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<div class="modal-custom-tour">
    <div class="modal-custom-tour__overlay"></div>
    <div class="modal-custom-tour__content">
        <button class="modal-custom-tour__close" aria-label="Close custom tour form">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <div class="modal-custom-tour__inner">
            <div class="form-custom-tour">
                <?php echo do_shortcode('[ninja_form id=3]') ?>
            </div>
        </div>
    </div>
</div>