<?php
/**
 * Category Tours - About Section
 *
 * Synchronized visually & structurally with Cars Template (`template-parts/cars/about-section.php`)
 * using `.hoi-an-intro` from the Hoi An Travel Design System, while preserving
 * Category Tours' own ACF fields (`hd_ab_cate_tours`, `sub_hd_ab_cate_tours`,
 * `desc_ab_cate_tours`, `gallerys_ab_cate_tours`).
 *
 * @package hoiantravel
 */

$heading  = function_exists('get_field') ? get_field('hd_ab_cate_tours') : '';
$sub_hd   = function_exists('get_field') ? get_field('sub_hd_ab_cate_tours') : '';
$desc     = function_exists('get_field') ? get_field('desc_ab_cate_tours') : '';
$gallerys = function_exists('get_field') ? get_field('gallerys_ab_cate_tours') : [];

// Normalize ACF gallery if present
$gallery_urls = [];
if (!empty($gallerys) && is_array($gallerys)) {
    foreach ($gallerys as $item) {
        if (is_array($item) && !empty($item['url'])) {
            $gallery_urls[] = [
                'url' => $item['url'],
                'alt' => !empty($item['alt']) ? $item['alt'] : __('Hoi An Ancient Heritage', 'hoiantravel'),
            ];
        } elseif (is_string($item) && !empty($item)) {
            $gallery_urls[] = [
                'url' => $item,
                'alt' => __('Hoi An Ancient Heritage', 'hoiantravel'),
            ];
        }
    }
}

// Curated default 4-photo set for Hoi An
$upload_info = wp_upload_dir();
$upload_base = !empty($upload_info['baseurl']) ? $upload_info['baseurl'] : '';

$curated_photos = [
    [
        'url' => $upload_base . '/2026/11/image-location-hoi-an-1024x683.webp',
        'alt' => 'Iconic yellow ancient alley with bougainvillea in Hoi An',
    ],
    [
        'url' => $upload_base . '/2026/11/feature-hoi-an-tour-1024x683.webp',
        'alt' => 'Hoai River lantern boat illuminated at dusk in Hoi An',
    ],
    [
        'url' => $upload_base . '/2026/11/imag-how-many-days-in-hoi-an-1024x682.webp',
        'alt' => 'Historic merchant shophouses and Japanese Bridge in Hoi An',
    ],
    [
        'url' => $upload_base . '/2026/11/img-location-Bay-Mau-Coconut-Forest-1024x683.webp',
        'alt' => 'Traditional lantern crafts and riverways of Hoi An',
    ],
];

// Fill 4 photo slots: ACF gallery first, then curated defaults
$photos = [];
for ($i = 0; $i < 4; $i++) {
    $photos[$i] = !empty($gallery_urls[$i]) ? $gallery_urls[$i] : $curated_photos[$i];
}

$display_eyebrow = !empty($sub_hd) ? $sub_hd : __('GATEWAY TO CENTRAL VIETNAM', 'hoiantravel');
$display_heading = !empty($heading) ? $heading : __('Explore Hoi An: Ancient Soul, Endless Discovery', 'hoiantravel');
?>

<section class="ha-section hoi-an-intro" id="category-tours-about">
    <div class="container">
        <div class="hoi-an-intro__grid">
            <!-- Left Column: 4-Photo Masonry / Collage Grid -->
            <div class="hoi-an-intro__gallery" aria-label="<?php esc_attr_e('Curated Hoi An photo gallery', 'hoiantravel'); ?>">
                <!-- Column 1 (Left): Top-Left Portrait + Bottom-Left Square -->
                <div class="hoi-an-intro__gallery-col">
                    <div class="hoi-an-intro__photo hoi-an-intro__photo--portrait">
                        <img src="<?= esc_url($photos[0]['url']); ?>" alt="<?= esc_attr($photos[0]['alt']); ?>"
                            loading="lazy" />
                    </div>
                    <div class="hoi-an-intro__photo hoi-an-intro__photo--square">
                        <img src="<?= esc_url($photos[1]['url']); ?>" alt="<?= esc_attr($photos[1]['alt']); ?>"
                            loading="lazy" />
                    </div>
                </div>

                <!-- Column 2 (Right): Top-Right Square + Bottom-Right Portrait -->
                <div class="hoi-an-intro__gallery-col">
                    <div class="hoi-an-intro__photo hoi-an-intro__photo--square">
                        <img src="<?= esc_url($photos[2]['url']); ?>" alt="<?= esc_attr($photos[2]['alt']); ?>"
                            loading="lazy" />
                    </div>
                    <div class="hoi-an-intro__photo hoi-an-intro__photo--portrait">
                        <img src="<?= esc_url($photos[3]['url']); ?>" alt="<?= esc_attr($photos[3]['alt']); ?>"
                            loading="lazy" />
                    </div>
                </div>
            </div>

            <!-- Right Column: Editorial Narrative Copy -->
            <div class="hoi-an-intro__content">
                <?php if (!empty($display_eyebrow)): ?>
                    <div class="ha-badge">
                        <span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-compass'); ?></span>
                        <span class="ha-badge__text"><?= esc_html($display_eyebrow); ?></span>
                    </div>
                <?php endif; ?>

                <h2 class="hoi-an-intro__heading">
                    <?= esc_html($display_heading); ?>
                </h2>

                <?php if (!empty($desc)): ?>
                    <div class="hoi-an-intro__body">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
