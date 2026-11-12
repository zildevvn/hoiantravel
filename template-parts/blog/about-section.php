<?php
$heading = get_field('hd_ab_blog_tpl');
$sub_heading = get_field('sub_hd_ab_blog_tpl');
$desc = get_field('desc_ab_blog_tpl');
$gallerys = get_field('gallerys_ab_blog_tpl');

// Build 4-item collage array from ACF gallery or fallbacks
$collage_items = [];
if (!empty($gallerys) && is_array($gallerys)) {
    $collage_items = array_values($gallerys);
    while (count($collage_items) < 4) {
        $collage_items = array_merge($collage_items, array_values($gallerys));
    }
    $collage_items = array_slice($collage_items, 0, 4);
} else {
    $fallback_images = [
        '/wp-content/uploads/2026/11/image-hoi-an-0002.jpg',
        '/wp-content/uploads/2026/11/location-My-Son-Sanctuary.webp',
        '/wp-content/uploads/2026/11/locaiton-Hue-Imperial-City-768x512.webp',
        '/wp-content/uploads/2026/11/img-faq-hoi-an-attractions-768x432.webp',
    ];
    foreach ($fallback_images as $fallback_url) {
        $collage_items[] = [
            'url' => home_url($fallback_url),
            'alt' => 'Hoi An Travel Experience',
        ];
    }
}
?>

<section class="ha-section blog-about-section">
    <div class="container">
        <div class="blog-about-section__wrap">
            <!-- Left Column: 4-Image Clover/Petal Grid + Center Badge -->
            <div class="blog-about-section__media">
                <div class="blog-about-collage">
                    <div class="blog-about-collage__grid">
                        <?php foreach ($collage_items as $index => $item):
                            $img_url = is_array($item) ? (!empty($item['sizes']['large']) ? $item['sizes']['large'] : $item['url']) : $item;
                            $img_alt = is_array($item) ? (!empty($item['alt']) ? $item['alt'] : 'Hoi An Travel') : 'Hoi An Travel';
                            ?>
                            <div class="blog-about-collage__item blog-about-collage__item--<?= $index + 1 ?>">
                                <img src="<?= esc_url($img_url); ?>" alt="<?= esc_attr($img_alt); ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Center Floating Badge -->
                    <div class="blog-about-collage__badge" aria-hidden="true">
                        <div class="blog-about-collage__badge-icon">
                            <?= ha_svg_icon('badge'); ?>
                        </div>
                        <span class="blog-about-collage__badge-title"> Hoi An Travel </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Existing Text Content -->
            <div class="blog-about-section__content">
                <?php if (!empty($sub_heading)): ?>
                    <span class="ha-badge">
                        <?= ha_svg_icon('badge'); ?>
                        <?= $sub_heading ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($heading)): ?>
                    <h2 class="ha-heading h3">
                        <?= $heading ?>
                    </h2>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <div class="blog-about-section__desc">
                        <?= $desc ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>