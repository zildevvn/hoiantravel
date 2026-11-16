<?php
$heading = get_field('hd_intro_tpl_ab');
$sub_hd = get_field('sub_hd_intro_tpl_ab');
$desc = get_field('desc_intro_tpl_ab');
$image = get_field('img_intro_tpl_ab');
$operator_license = get_field('operator_license', 'option');
$license_image = $operator_license['image'];
$license_desc = $operator_license['description'];
?>
<section class="ha-section intro-section">
    <div class="container">
        <?php ha_icon_heading() ?>

        <?php if (!empty($sub_hd)): ?>
            <h3 class="ha-heading-intro h5"> <?= $sub_hd ?> </h3>
        <?php endif; ?>

        <?php if (!empty($heading)): ?>
            <h2 class="ha-heading"> <?= $heading ?> </h2>
        <?php endif; ?>

        <div class="intro-section-media">
            <div class="intro-section-media__text">
                <div class="media-content">
                    <?php if (!empty($desc)): ?>
                        <?= $desc ?>
                    <?php endif; ?>

                    <div class="media-meta">
                        <button type="button" class="ha-button btn-view-license">
                            View Operator License
                            <?= ha_svg_icon('arrow-up-right'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="intro-section-media__image">
                <?php if (!empty($image)): ?>
                    <img src="<?= esc_url($image) ?>" alt="Image intro for Phu Quoc Travel Agency">
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($license_image)): ?>
    <div class="ha-license-modal" id="licenseModal">
        <div class="ha-license-modal__overlay"></div>
        <div class="ha-license-modal__content">
            <button type="button" class="ha-license-modal__close" aria-label="Close modal">
                <?= ha_svg_icon('x-close'); ?>
            </button>
            <div class="ha-license-modal__body">
                <div class="ha-license-modal__grid">
                    <div class="ha-license-modal__left">
                        <div class="ha-license-modal__image">
                            <img src="<?= esc_url($license_image) ?>" alt="Operator License" class="img-fluid"
                                loading="lazy" width="312" height="410" />
                        </div>
                    </div>
                    <div class="ha-license-modal__right">
                        <h3 class="ha-license-modal__title h4">About ha Travel Company</h3>
                        <?php if (!empty($license_desc)): ?>
                            <div class="ha-license-modal__desc">
                                <?= wp_kses_post($license_desc) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>