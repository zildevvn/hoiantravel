<?php

$sliders = get_field('sliders_hero_home');
?>
<?php if (!empty($sliders) && is_array($sliders)): ?>
    <?php
    $slider_count = count($sliders);
    ?>
    <section class="ha-section hero-section" aria-label="<?php esc_attr_e('Featured Highlights', 'hoiantravel'); ?>">
        <div class="hero-section__sliders swiper">
            <div class="swiper-wrapper">
                <?php foreach ($sliders as $index => $slider): ?>
                    <?php
                    $slide_title   = $slider['title'] ?? '';
                    $slide_heading = $slider['heading'] ?? '';
                    $slide_desc    = $slider['desc'] ?? '';
                    $slide_img_url = $slider['image']['url'] ?? '';
                    $slide_img_alt = !empty($slider['image']['alt']) ? $slider['image']['alt'] : $slide_heading;
                    $slide_btn     = $slider['button'] ?? null;
                    $heading_tag   = ($index === 0) ? 'h1' : 'h2';
                    ?>
                    <div class="swiper-slide hero-slider-item" data-title="<?= esc_attr($slide_title) ?>">
                        <div class="hero-slider-item__bg">
                            <?php if (!empty($slide_img_url)): ?>
                                <img src="<?= esc_url($slide_img_url) ?>" alt="<?= esc_attr($slide_img_alt) ?>" <?= $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' ?> />
                            <?php endif; ?>
                        </div>
                        <div class="container">
                            <div class="hero-slider-item__content">
                                <?php if (!empty($slide_title)): ?>
                                    <div class="hero-slider-item__badge-wrap">
                                        <span class="ha-badge ha-badge--on-dark">
                                            <span class="ha-badge__icon" aria-hidden="true"><?php echo ha_svg_icon('feature-lantern'); ?></span>
                                            <span class="ha-badge__text"><?= esc_html($slide_title) ?></span>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($slide_heading)): ?>
                                    <<?= $heading_tag ?> class="hero-slider-item__heading h1"><?= esc_html($slide_heading) ?></<?= $heading_tag ?>>
                                <?php endif; ?>

                                <?php if (!empty($slide_desc)): ?>
                                    <p class="hero-slider-item__desc"><?= esc_html($slide_desc) ?></p>
                                <?php endif; ?>

                                <div class="hero-slider-item__action d-flex align-items-center gap-3 flex-wrap">
                                    <?php if (!empty($slide_btn) && !empty($slide_btn['url'])): ?>
                                        <a href="<?= esc_url($slide_btn['url']) ?>"
                                            target="<?= esc_attr(!empty($slide_btn['target']) ? $slide_btn['target'] : '_self') ?>"
                                            class="ha-button">
                                            <?= esc_html(!empty($slide_btn['title']) ? $slide_btn['title'] : __('Explore Now', 'hoiantravel')) ?>
                                            <?php echo ha_svg_icon('arrow_next'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?php echo esc_url(home_url('/hoi-an-holiday-packages/')) ?>"
                                        class="ha-button ha-button--outline">
                                        <?php esc_html_e('Plan Custom Trip', 'hoiantravel'); ?>
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
