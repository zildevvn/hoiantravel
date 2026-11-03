<?php
$heading = get_field('hd_qf_ss_tour_cate_tpl');
$desc = get_field('desc_qf_ss_tour_cate_tpl');
$image = get_field('img_qf_ss_tour_cate_tpl');
?>
<section class="vm-section quick-facts-section">
    <div class="container">
        <div class="vm-media">
            <?php if (!empty($image)): ?>
                <div class="vm-media__image">
                    <?php $alt = !empty($image['alt']) ? $image['alt'] : 'image for quick facts about phu quoc island'; ?>
                    <img src="<?= $image['sizes']['medium_large'] ?>" alt="<?= $alt ?>" />
                </div>
            <?php endif; ?>

            <div class="vm-media__content">
                <?php if (!empty($heading)): ?>
                    <h2 class="vm-heading h3">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <div class="content">
                    <?php if (!empty($desc)): ?>
                        <div class="desc">
                            <?= $desc ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>