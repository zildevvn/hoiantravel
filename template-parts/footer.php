<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$about_text = get_field('about_text_ft', 'option');
$address = get_field('address', 'option');
$email = get_field('email', 'option');
$phone = get_field('phone', 'option');
$copyright = get_field('copyright_ft', 'option');
$socials = get_field('socials', 'option');
$bg_footer = get_field('background_image', 'option');
?>



<footer class="main-footer">
    <div class="main-footer-warp">
        <div class="container">
            <div class="main-footer-top d-flex justify-content-between align-items-end">
                <div class="main-footer__logo">
                    <a class="d-flex " href="<?php echo home_url(); ?>"
                        aria-label="<?php echo get_bloginfo('name'); ?>">
                        <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>" width="200"
                            height="54" />
                    </a>
                </div>

                <div class="main-footer-top__right d-flex justify-content-between">
                    <?php if (!empty($phone)): ?>
                        <?php
                        $phone = preg_replace('/\D+/', '', $phone);
                        if (strpos($phone, '0') === 0) {
                            $phone = '84' . substr($phone, 1);
                        }
                        ?>
                        <div class="main-footer__phone">
                            <h2 class="h5">24/7 Support</h2>
                            <a href="https://wa.me/<?= esc_attr($phone); ?>" target="_blank" rel="noopener noreferrer"
                                aria-label="Chat with us on WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-phone-forwarded">
                                    <path d="M14 6h8" />
                                    <path d="m18 2 4 4-4 4" />
                                    <path
                                        d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
                                </svg>
                                +<?= $phone ?>
                            </a>

                        </div>
                    <?php endif; ?>

                    <?php if (!empty($email)): ?>
                        <div class="main-footer__email">
                            <h2 class="h5">Email Us</h2>
                            <a href="mailto:<?= $email ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-mail-check-icon lucide-mail-check">
                                    <path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8"></path>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    <path d="m16 19 2 2 4-4"></path>
                                </svg>
                                <?= $email ?>
                            </a>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($address)): ?>
                        <div class="main-footer__address">
                            <h2 class="h5">Our Location </h2>
                            <p class="mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-map-pin-check">
                                    <path
                                        d="M19.43 12.935c.357-.967.57-1.955.57-2.935a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 1.202 0 32.197 32.197 0 0 0 .813-.728" />
                                    <circle cx="12" cy="10" r="3" />
                                    <path d="m16 18 2 2 4-4" />
                                </svg>
                                <?= $address ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="main-footer-middle d-flex justify-content-between">
                <?php if (!empty($about_text)): ?>
                    <div class="main-footer__about">
                        <h2 class="h5">About Us</h2>
                        <p class="mb-0"> <?= $about_text ?> </p>
                    </div>
                <?php endif; ?>

                <div class="main-footer__menus d-flex justify-content-between">

                    <?php if (has_nav_menu('company-menu')): ?>
                        <div class="main-footer__menu company-menu">
                            <h2 class="h5">Company</h2>
                            <?php wp_nav_menu(array('theme_location' => 'company-menu', 'menu_class' => 'company-menu')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (has_nav_menu('daily-tours-menu')): ?>
                        <div class="main-footer__menu daily-tours-menu">
                            <h2 class="h5">Day Trip</h2>
                            <?php wp_nav_menu(array('theme_location' => 'daily-tours-menu', 'menu_class' => 'daily-tours-menu')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (has_nav_menu('package-tours-menu')): ?>
                        <div class="main-footer__menu package-tours-menu">
                            <h2 class="h5">Package Tours</h2>
                            <?php wp_nav_menu(array('theme_location' => 'package-tours-menu', 'menu_class' => 'package-tours-menu')) ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="main-footer-bottom d-flex justify-content-between align-items-center">
                <?php if (!empty($copyright)): ?>
                    <p class="mb-0 main-footer__copyright"><?= $copyright ?></p>
                <?php endif; ?>


                <?php if (!empty($socials)): ?>
                    <div class="main-footer__socials d-flex align-items-center">
                        <?php foreach ($socials as $key => $social): ?>
                            <div class="item-social">
                                <a href="<?= $social['link'] ?>" class="d-flex align-items-center justify-content-center">
                                    <img src="<?= $social['icon'] ?>" alt="icon-footer <?= $key + 1 ?>" width="24"
                                        height="24" />
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php get_template_part('template-parts/modal-search'); ?>

    <a href="#" class="back-to-top" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-up">
            <path d="m18 15-6-6-6 6" />
        </svg>
    </a>

    <?php if (!empty($phone)): ?>
        <?php
        $phone = preg_replace('/\D+/', '', $phone);
        if (strpos($phone, '0') === 0) {
            $phone = '84' . substr($phone, 1);
        }
        ?>
        <a class="btn-whatsapp" href="https://wa.me/<?= esc_attr($phone); ?>" target="_blank" rel="noopener noreferrer"
            aria-label="Chat with us on WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-phone-forwarded preview-icon">
                <path d="M14 6h8" />
                <path d="m18 2 4 4-4 4" />
                <path
                    d="M13.832 16.568a1 1 0 0 0 1.213-.303l.355-.465A2 2 0 0 1 17 15h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2A18 18 0 0 1 2 4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-.8 1.6l-.468.351a1 1 0 0 0-.292 1.233 14 14 0 0 0 6.392 6.384" />
            </svg>
        </a>
    <?php endif; ?>
</footer>