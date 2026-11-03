<?php
$title = get_the_title();
$image = get_the_post_thumbnail_url();
$desc = get_the_content();
// vm_hero_section_shared($title, $image, $desc);
?>
<section class="vm-section hero-section-shared">
    <div class="vm-section__bg">
        <img src="<?= $image ?>" alt="background image for hero <?= $title ?>" />
    </div>

    <div class="container">
        <div class="hero-section-shared__box">
            <?php vm_breadcrumbs($title) ?>
            <h1 class="vm-heading"><?= $title ?></h1>

            <?php if (!empty($desc)): ?>
                <div class="hero-section-shared__desc">
                    <?= $desc ?>
                </div>
            <?php endif; ?>


            <div class="hero-section-shared__ratings">
                <?php if (function_exists('kk_star_ratings')): ?>
                    <?= kk_star_ratings(); ?>
                <?php endif; ?>
            </div>

            <div class="hero-section-shared__btn mt-4">
                <?php vm_load_button('#form-customize-tour', 'Customize Now', '', 'Customize Tour Now') ?>
            </div>
        </div>
    </div>
</section>