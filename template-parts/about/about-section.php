<?php
$heading = get_field('hd_ab_tpl');
$sub_hd = get_field('sub_hd_ab_tpl');
$desc = get_field('desc_ab_tpl');
$image_ab = get_field('image_ab_tpl');
$media = get_field('media_list');
$operator_license = get_field('operator_license', 'option');
$license_image = $operator_license['image'];
$license_desc = $operator_license['description'];
?>
<section class="ha-section about-section">
    <div class="about-section__graphic">
        <img src="<?= get_template_directory_uri(); ?>/assets/images/img-graphic-001.png"
            alt="image graphic phu quoc island tours" />
    </div>

    <div class="container">
        <?php ha_icon_heading() ?>
        <?php if (!empty($heading)): ?>
            <h2 class="ha-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($sub_hd)): ?>
            <p class="ha-sub-heading">
                <?= $sub_hd ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($media)): ?>
            <div class="about-section__grid">
                <?php foreach ($media as $item): ?>
                    <div class="media-item ha-media">
                        <div class="ha-media__image">
                            <img src="<?= $item['image']['sizes']['large'] ?>"
                                alt="image phu quoc travel agency for <?= $item['heading'] ?>" />
                        </div>
                        <div class="ha-media__content">
                            <?php if (!empty($item['heading'])): ?>
                                <h2 class="h4">
                                    <?= $item['heading'] ?>
                                </h2>
                            <?php endif; ?>

                            <div class="content">
                                <?= $item['desc'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>