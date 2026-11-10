<?php
$heading = get_field('hd_ab_tpl_tour');
$sub_hd = get_field('sub_hd_ab_tpl_tour');
$desc = get_field('desc_ab_tpl_tour');
$gallerys = get_field('gallery_ab_tpl_tour');

// Normalize ACF gallery if present
$gallery_urls = [];
if (!empty($gallerys) && is_array($gallerys)) {
    foreach ($gallerys as $item) {
        if (is_array($item) && !empty($item['url'])) {
            $gallery_urls[] = [
                'url' => $item['url'],
                'alt' => !empty($item['alt']) ? $item['alt'] : 'Hoi An Ancient Heritage',
            ];
        } elseif (is_string($item) && !empty($item)) {
            $gallery_urls[] = [
                'url' => $item,
                'alt' => 'Hoi An Ancient Heritage',
            ];
        }
    }
}

// Curated default 4-photo set for Hoi An
$upload_info = wp_upload_dir();
$upload_base = $upload_info['baseurl'];

$curated_photos = [
    // Top-Left: Portrait vertical shot (iconic yellow ancient alley with bougainvillea)
    [
        'url' => $upload_base . '/2026/11/image-location-hoi-an-1024x683.webp',
        'alt' => 'Iconic yellow ancient alley with bougainvillea in Hoi An',
    ],
    // Bottom-Left: Landscape/square shot (Hoai River lantern boat at dusk)
    [
        'url' => $upload_base . '/2026/11/feature-hoi-an-tour-1024x683.webp',
        'alt' => 'Hoai River lantern boat illuminated at dusk in Hoi An',
    ],
    // Top-Right: Landscape/square shot (historic merchant shophouses / Japanese Bridge)
    [
        'url' => $upload_base . '/2026/11/imag-how-many-days-in-hoi-an-1024x682.webp',
        'alt' => 'Historic merchant shophouses and Japanese Bridge in Hoi An',
    ],
    // Bottom-Right: Portrait vertical shot (traditional artisan / river life)
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

$display_eyebrow = !empty($sub_hd) ? $sub_hd : 'GATEWAY TO CENTRAL VIETNAM';
$display_heading = !empty($heading) ? $heading : 'Explore Hoi An: Ancient Soul, Endless Discovery';
?>

<section class="ha-section hoi-an-intro" id="tours-about">
    <div class="container">
        <div class="hoi-an-intro__grid">
            <!-- Left Column: 4-Photo Masonry / Collage Grid -->
            <div class="hoi-an-intro__gallery" aria-label="Curated Hoi An photo gallery">
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
                        <?php echo ha_svg_icon('badge'); ?>
                        <?= esc_html($display_eyebrow); ?>
                    </div>
                <?php endif; ?>

                <h2 class="hoi-an-intro__heading">
                    <?= esc_html($display_heading); ?>
                </h2>

                <div class="hoi-an-intro__body">
                    <?php if (!empty($desc)): ?>
                        <?= wp_kses_post($desc); ?>
                    <?php else: ?>
                        <p>Immerse yourself in a living open-air gallery of saffron-washed merchant facades, romantic timber
                            footbridges, and evening riverbanks illuminated by thousands of floating paper lanterns. Hoi An
                            captures Southeast Asia’s most romantic heritage with effortless grace.</p>
                        <p>Beyond its quiet pedestrian alleys, the ancient port acts as the premier launchpad for Central
                            Vietnam. Seamlessly transition from sacred ancient mountain valleys and marble cliffs to
                            imperial capitals—all within an easy day trip.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>