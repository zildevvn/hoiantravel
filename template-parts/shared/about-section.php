<?php
$heading = $args['heading'] ?? '';
$sub_hd = $args['sub_heading'] ?? '';
$desc = $args['description'] ?? '';
$gallerys = $args['gallery'] ?? [];
$alt_text = $args['alt_text'] ?? '';
$append_content = $args['append_content'] ?? '';
$prepend_section = $args['prepend_section'] ?? '';
$counters = $args['counters'] ?? [];
?>
<section class="ha-section about-section ha-media-carousel-section">
    <?= $prepend_section ?>
    <div class="container">

        <?php if (!empty($heading)): ?>
            <h2 class="ha-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($sub_hd)): ?>
            <div class="ha-sub-heading">
                <?= wp_kses_post($sub_hd) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($counters)): ?>
            <div class="ha-counters">
                <?php foreach ($counters as $key => $counter): ?>
                    <div class="counter-item">
                        <div class="counter-item__icon">
                            <img src="<?= $counter['icon'] ?>" alt="icon for <?= $counter['label'] ?>" width="50" height="50" />
                        </div>

                        <div class="counter-item-content">
                            <h2 class="h6">
                                <?= $counter['label'] ?>
                            </h2>
                            <p class="ha-counter mb-0 h2">
                                <?= $counter['number'] ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="ha-media-carousel-section__media">
            <div class="content">
                <?php if (!empty($desc)): ?>
                    <?= wp_kses_post($desc) ?>
                <?php endif; ?>
                <?= $append_content ?>
            </div>

            <?php if (!empty($gallerys)): ?>
                <div class="gallerys gallerys-carousel swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($gallerys as $key => $gallery): ?>
                            <div class="gallery-item swiper-slide">
                                <img src="<?= esc_url($gallery) ?>"
                                    alt="image <?= esc_attr($key) ?> for <?= esc_attr($alt_text) ?>" loading="lazy" width="534"
                                    height="357" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="swiper-button-prev">
                        <?= ha_svg_icon('arrow-left-long'); ?>
                    </div>
                    <div class="swiper-button-next">
                        <?= ha_svg_icon('arrow-right-long'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>