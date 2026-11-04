<?php

$sliders = get_field('sliders_hero_home');
?>
<?php if (!empty($sliders)): ?>
    <?php
    $slider_count = count($sliders);
    ?>
    <section class="ha-section hero-section">
        <div class="hero-section__sliders swiper">
            <div class="swiper-wrapper">
                <?php foreach ($sliders as $slider): ?>
                    <div class="swiper-slide hero-slider-item" data-title="<?= esc_attr($slider['title'] ?? '') ?>">
                        <div class="hero-slider-item__bg">
                            <img src="<?= $slider['image']['url'] ?>" alt="<?= $slider['image']['alt'] ?>" />
                        </div>
                        <div class="container">
                            <div class="hero-slider-item__content">
                                <h1><?= $slider['heading'] ?></h1>
                                <p><?= $slider['desc'] ?></p>
                                <div class="hero-slider-item__action d-flex align-items-center gap-3 flex-wrap">
                                    <?php if (!empty($slider['button'])): ?>
                                        <a href="<?= $slider['button']['url'] ?>" target="<?= $slider['button']['target'] ?>"
                                            class="ha-button">
                                            <?= $slider['button']['title'] ?>
                                            <?php echo ha_svg_icon('arrow_next'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo home_url('/hoi-an-holiday-packages/') ?>"
                                        class="ha-button ha-button--outline">
                                        Plan Custom Trip
                                        <?php echo ha_svg_icon('arrow_next'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($slider_count > 1): ?>
                <div class="hero-slider-controls">
                    <div class="hero-slider-controls__inner">
                        <!-- Left: Slide Information -->
                        <div class="hero-slider-controls__info">
                            <div class="hero-slider-controls__counter">
                                <span class="hero-slider-controls__current">01</span>
                                <span class="hero-slider-controls__sep">/</span>
                                <span class="hero-slider-controls__total"><?= sprintf('%02d', $slider_count) ?></span>
                            </div>
                            <div class="hero-slider-controls__title"><?= esc_html($sliders[0]['title'] ?? '') ?></div>
                        </div>

                        <!-- Center: Segmented Progress -->
                        <div class="hero-slider-controls__progress" role="tablist" aria-label="Slide progress">
                            <?php for ($i = 0; $i < $slider_count; $i++): ?>
                                <button type="button" class="hero-slider-progress-segment <?= $i === 0 ? 'is-active' : '' ?>"
                                    data-index="<?= $i ?>" aria-label="<?= esc_attr(sprintf('Go to slide %d', $i + 1)) ?>">
                                    <span class="hero-slider-progress-segment__fill"></span>
                                </button>
                            <?php endfor; ?>
                        </div>

                        <!-- Right: Navigation -->
                        <div class="hero-slider-controls__nav">
                            <button type="button" class="hero-slider-nav-btn hero-slider-nav-btn--prev"
                                aria-label="Previous slide">
                                <?php echo ha_svg_icon('arrow_prev'); ?>
                            </button>
                            <button type="button" class="hero-slider-nav-btn hero-slider-nav-btn--next" aria-label="Next slide">
                                <?php echo ha_svg_icon('arrow_next'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>