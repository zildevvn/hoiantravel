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
<section class="vm-section about-section vm-media-carousel-section">
    <?= $prepend_section ?>
    <div class="container">
        <?php vm_icon_heading(); ?>
        <?php if (!empty($heading)): ?>
            <h2 class="vm-heading">
                <?= $heading ?>
            </h2>
        <?php endif; ?>

        <?php if (!empty($sub_hd)): ?>
            <div class="vm-sub-heading">
                <?= wp_kses_post($sub_hd) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($counters)): ?>
            <div class="vm-counters">
                <?php foreach ($counters as $key => $counter): ?>
                    <div class="counter-item">
                        <div class="counter-item__icon">
                            <img src="<?= $counter['icon'] ?>" alt="icon for <?= $counter['label'] ?>" width="50" height="50" />
                        </div>

                        <div class="counter-item-content">
                            <h2 class="h6">
                                <?= $counter['label'] ?>
                            </h2>
                            <p class="vm-counter mb-0 h2">
                                <?= $counter['number'] ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="vm-media-carousel-section__media">
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
                        <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="#000000">
                            <path d="M21 12L3 12M3 12L11.5 3.5M3 12L11.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="swiper-button-next">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="#000000">
                            <path d="M3 12L21 12M21 12L12.5 3.5M21 12L12.5 20.5" stroke="#000000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>