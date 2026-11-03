<?php

$hd = get_field('hd_hero_home');
$desc = get_field('desc_hero_home');
$show_vd = get_field('show_vd_hero_home');
$gallerys = get_field('gallerys_hero_hp');
$video = get_field('video_hero_hp');

$whatsapp_display = get_field('phone', 'option');
$whatsapp_number = get_field('whatsapp', 'option');
if (empty($whatsapp_number)) {
    $whatsapp_number = $whatsapp_display;
}

$whatsapp_url = '';
if (!empty($whatsapp_number)) {
    // Sanitize the phone number
    $clean_number = preg_replace('/[^0-9]/', '', $whatsapp_number);
    // Auto-append country code for local numbers
    if (strpos($clean_number, '0') === 0) {
        $clean_number = '84' . substr($clean_number, 1);
    }
    // Set predefined message
    $message = rawurlencode("Hello! I'm interested in your tours. Could you provide more information?");
    $whatsapp_url = "https://wa.me/{$clean_number}?text={$message}";
}
?>
<section class="vm-section hero-section">
    <div class="hero-section-inner">
        <div class="hero-section__bg">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/overlay-shape-green.png" width="1920"
                height="1080" alt="background graphic hero for phu quoc island tour" fetchpriority="high" />
            <div class="hero-section-content">
                <?php if (!empty($hd)): ?>
                    <h1><?= $hd ?></h1>
                <?php endif; ?>

                <?php if (!empty($desc)): ?>
                    <div class="hero-section__desc"> <?= $desc ?> </div>
                <?php endif; ?>

                <div class="hero-section__contact gap-3 d-flex align-items-center">
                    <?php echo do_shortcode('[tripadvisor_badge]'); ?>

                    <?php if (!empty($whatsapp_url) && !empty($whatsapp_display)): ?>
                        <a href="<?= esc_url($whatsapp_url) ?>" target="_blank" rel="noopener noreferrer"
                            aria-label="Chat with us on WhatsApp"
                            class="hero-section__whatsapp d-flex align-items-center gap-2">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="#000000">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C10.1409 22.75 8.39016 22.2775 6.86335 21.4455L2.12395 22.2397C1.88692 22.2794 1.6452 22.2031 1.47391 22.0345C1.30261 21.8659 1.2225 21.6255 1.25845 21.3878L2.05878 16.0977C1.53735 14.8339 1.25001 13.4496 1.25001 12C1.25001 6.06294 6.06295 1.25 12 1.25ZM7.94309 6.7002C7.20774 6.7002 6.599 7.32056 6.71374 8.08595C6.929 9.52188 7.56749 12.1676 9.46536 14.0799C11.4494 16.0789 14.2876 16.9343 15.8259 17.2715C16.6211 17.4459 17.3 16.8158 17.3 16.0387V14.2151C17.3 14.0909 17.2235 13.9796 17.1076 13.935L15.1475 13.1825C15.0949 13.1623 15.0377 13.1573 14.9824 13.1681L13.0048 13.5542C11.7304 12.894 10.958 12.1532 10.4942 11.0387L10.867 9.02365C10.8769 8.97021 10.8721 8.91508 10.8531 8.86416L10.1182 6.89529C10.0744 6.77797 9.96233 6.7002 9.83711 6.7002H7.94309Z"
                                    fill="#000000"></path>
                            </svg>
                            <div class="content h5 mb-0">
                                <p class="mb-0 h4">whatsapp</p>
                                <?= esc_html($whatsapp_display) ?>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (function_exists('kk_star_ratings')): ?>
                    <div class="hero-section__ratings">
                        <?php echo kk_star_ratings(); ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <?php if ($show_vd && !empty($video)): ?>
            <div class="hero-section-video">
                <div class="gallery-item">
                    <div class="gallery-item__media">
                        <video class="gallery-item__video lazy-video" autoplay muted loop playsinline preload="metadata"
                            poster="<?= get_template_directory_uri(); ?>/assets/images/img-poster.webp" width="840"
                            height="760">
                            <source src="<?= $video ?>" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ phát video.
                        </video>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!$show_vd && !empty($gallerys)): ?>
            <div class="hero-section-gallery swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($gallerys as $gallery): ?>
                        <div class="swiper-slide gallery-item">
                            <div class="gallery-item__media">
                                <img src="<?= $gallery ?>" alt="image gallery">
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="hero-section__watercolor-top">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/image_wave_top_new.webp"
                alt="image leaf top for hero section" width="170" height="107" />
        </div>

        <div class="hero-section__baloon-top">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/img_top_island.webp"
                alt="image baloon top for hero section" width="150" height="100" />
        </div>

        <div class="hero-section__watercolor-bottom">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/img-seashells-starfish-waves.webp"
                alt="image leaf bottom for hero section" width="220" height="91" />
        </div>

        <div class="hero-section__baloon-bottom">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/img_top_island.png"
                alt="image baloon bottom for hero section" width="150" height="100" />
        </div>
    </div>
</section>