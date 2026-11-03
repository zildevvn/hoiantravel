<?php
$heading = get_field('hd_ab_home');
$sub_hd = get_field('sub_hd_ab_home');
$desc = get_field('desc_ab_home');
$image_ab = get_field('image_ab');
$operator_license = get_field('operator_license', 'option');
$license_image = $operator_license['image'];
$license_desc = $operator_license['description'];
?>
<section class="vm-section about-section">
    <div class="about-section__graphic">
        <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
            alt="image about graphic phu quoc island tours" width="300" height="260" />
    </div>

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

        <div class="about-section__content d-flex align-items-center justify-content-between">
            <?php if (!empty($image_ab['url'])): ?>
                <div class="about-section__license_image">
                    <img src="<?= $image_ab['url'] ?>" alt="image about phu quoc island tours" class="img-fluid"
                        loading="lazy" width="536" height="357" />
                </div>
            <?php endif; ?>

            <?php if (!empty($desc)): ?>
                <div class="about-section__desc">
                    <?= $desc ?>
                    <?php if (!empty($license_image)): ?>
                        <div class="about-section__desc-action mt-4">
                            <button type="button" class="vm-button btn-view-license">
                                View Operator License

                                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" color="#000000">
                                    <path d="M12 21L12 3M12 3L20.5 11.5M12 3L3.5 11.5" stroke="#000000" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($license_image)): ?>
    <div class="vm-license-modal" id="licenseModal">
        <div class="vm-license-modal__overlay"></div>
        <div class="vm-license-modal__content">
            <button type="button" class="vm-license-modal__close" aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="vm-license-modal__body">
                <div class="vm-license-modal__grid">
                    <div class="vm-license-modal__left">
                        <div class="vm-license-modal__image">
                            <img src="<?= esc_url($license_image) ?>" alt="Operator License" class="img-fluid"
                                loading="lazy" width="312" height="410" />
                        </div>
                    </div>
                    <div class="vm-license-modal__right">
                        <h3 class="vm-license-modal__title h4">About VM Travel Company</h3>
                        <?php if (!empty($license_desc)): ?>
                            <div class="vm-license-modal__desc">
                                <?= wp_kses_post($license_desc) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>