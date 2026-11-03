<?php
$heading = get_field('heading_fa_ss_tour_cate_tpl');
$sub_hd = get_field('sub_h_fa_ss_tour_cate_tpl');
?>
<section class="vm-section famous-attractions-section">
    <div class="container">
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
    </div>
</section>