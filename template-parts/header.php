<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$socials = get_field('socials', 'option');
$sologan = get_field('sologan_hd_top', 'option');
$email = get_field('email', 'option');
?>



<header id="site-header" class="header-main">
    <div class="header-main-inner">
        <div class="header-top d-flex align-items-center justify-content-between">
            <div class="header-top__sologan">
                <?php if (!empty($sologan)): ?>
                    <p class="mb-0">
                        <?= $sologan ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="header-top__right d-flex align-items-center justify-content-end gap-3">

                <?php if (!empty($email)): ?>
                    <a class="header-top__email d-flex align-items-center justify-content-center"
                        href="mailto:<?= $email ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22 4 12 14 2 4"></polyline>
                        </svg>
                        <?= $email ?>
                    </a>
                <?php endif; ?>


                <?php if (!empty($socials)): ?>
                    <div class="header-top__socials d-flex align-items-center">
                        <?php foreach ($socials as $social): ?>
                            <div class="item-social">
                                <a href="<?= $social['link'] ?>" class="d-flex align-items-center justify-content-center">
                                    <img src="<?= $social['icon'] ?>" alt="icon-footer">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="header-main-warp d-flex align-items-center justify-content-between gap-3">
            <div class="header-main__logo">
                <a class="d-flex " href="<?php echo home_url(); ?>" aria-label="<?php echo get_bloginfo('name'); ?>">
                    <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>" width="200"
                        height="54" />
                </a>
            </div>

            <div class="header-main__menu d-none d-xl-block">
                <?php if (has_nav_menu('primary-menu')): ?>
                    <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'primary-menu')) ?>
                <?php endif; ?>
            </div>

            <div class="header-main__actions d-flex align-items-center justify-content-end gap-3">
                <button id="btn_search" class="header-main__search d-flex align-items-center justify-content-center"
                    aria-label="<?php esc_attr_e('Open search', 'vm'); ?>" type="button">
                    <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 17L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path
                            d="M3 11C3 15.4183 6.58172 19 11 19C13.213 19 15.2161 18.1015 16.6644 16.6493C18.1077 15.2022 19 13.2053 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>

                <div class="header-main__translate">
                    <?php echo do_shortcode('[gtranslate]'); ?>
                </div>

                <button id="mobile-side-drawer" data-target="#offcanvas-menu" type="button" class="d-xl-none d-block"
                    aria-expanded="false" aria-controls="offcanvas-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar icon-bar-first"></span>
                    <span class="icon-bar icon-bar-two"></span>
                    <span class="icon-bar icon-bar-three"></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas-overlay" aria-hidden="true" tabindex="-1"></div>
<div id="offcanvas-menu" class="offcanvas-menu d-xl-none" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="offcanvas-menu__header d-flex align-items-center justify-content-between">
        <div class="offcanvas-menu__logo">
            <a class="d-flex" href="<?php echo home_url(); ?>" aria-label="<?php echo get_bloginfo('name'); ?>">
                <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>">
            </a>
        </div>
        <button class="offcanvas-menu__close d-flex align-items-center justify-content-center"
            aria-label="<?php esc_attr_e('Close menu', 'hle'); ?>" type="button">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="offcanvas-menu__body">
        <?php if (has_nav_menu('primary-menu')): ?>
            <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'offcanvas-menu__nav')) ?>
        <?php endif; ?>
    </div>
</div>