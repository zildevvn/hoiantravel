<?php
$heading = get_field('hd_travel_essentials');
$sub_heading = get_field('sub_hd_travel_essentials');
$content = get_field('content_travel_essentials');
$image = get_field('image_travel_essentials');
?>
<section class="vm-section travel-essentials-section">
    <div class="container">
        <div class="vm-media">
            <div class="vm-media__content">
                <?php if (!empty($sub_heading)): ?>
                    <p class="sub-heading h6">
                        <?= $sub_heading ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="h3">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($content)): ?>
                    <div class="content">
                        <?= $content ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="vm-media__image">
                <?php if (!empty($image)): ?>
                    <img src="<?= $image ?>" alt="image travel essentials for Phu Quoc Island Travel Guide" />
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>