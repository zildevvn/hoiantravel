<?php
$custom_logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_url($custom_logo_id);
$socials = get_field('socials', 'option');
$sologan = get_field('sologan_hd_top', 'option');
$email = get_field('email', 'option');
?>



<header id="site-header" class="header-main">
    <div class="container">
        <div class="header-main-inner d-flex align-items-center justify-content-between">
            <div class="header-main__logo">
                <a class="d-flex " href="<?php echo home_url(); ?>" aria-label="<?php echo get_bloginfo('name'); ?>">
                    <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>" width="200"
                        height="54" />
                </a>
            </div>

            <div class="header-main__menu d-none d-xl-block">
                <?php if (has_nav_menu('primary-menu')): ?>
                    <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'menu_class' => 'primary-menu d-flex')) ?>
                <?php endif; ?>
            </div>

            <div class="header-main__action d-flex align-items-center justify-content-end gap-2 gap-sm-3">
                <button id="btn_search" class="header-main__search d-flex align-items-center justify-content-center"
                    aria-label="<?php esc_attr_e('Open search', 'ha'); ?>" type="button">
                    <?php echo ha_svg_icon('search-icon'); ?>
                </button>

                <div class="header-main__divider d-none d-xl-block"> </div>

                <a class="ha-button d-none d-md-inline-flex">
                    Book Now
                    <?php echo ha_svg_icon('arrow_next'); ?>
                </a>

                <button id="btn_hamburger" class="header-main__hamburger d-flex d-xl-none align-items-center justify-content-center"
                    aria-label="<?php esc_attr_e('Open navigation menu', 'ha'); ?>" aria-controls="mobile-menu-drawer" aria-expanded="false" type="button">
                    <?php echo ha_svg_icon('hamberger-icon'); ?>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Navigation Drawer & Overlay -->
<div id="mobile-menu-overlay" class="mobile-menu-overlay d-xl-none" aria-hidden="true" tabindex="-1"></div>

<aside id="mobile-menu-drawer" class="mobile-menu-drawer d-xl-none" role="dialog" aria-modal="true"
    aria-label="<?php esc_attr_e('Mobile Navigation', 'ha'); ?>" aria-hidden="true">
    <div class="mobile-menu-drawer__header d-flex align-items-center justify-content-between">
        <div class="mobile-menu-drawer__logo">
            <a class="d-flex" href="<?php echo home_url(); ?>" aria-label="<?php echo get_bloginfo('name'); ?>">
                <img src="<?php echo $logo_url; ?>" alt="<?php echo get_bloginfo('name'); ?>" width="160" height="43" />
            </a>
        </div>
        <button id="btn_close_menu" class="mobile-menu-drawer__close d-flex align-items-center justify-content-center"
            aria-label="<?php esc_attr_e('Close navigation menu', 'ha'); ?>" type="button">
            <?php echo ha_svg_icon('menu-close-icon'); ?>
        </button>
    </div>

    <div class="mobile-menu-drawer__body">
        <?php if (has_nav_menu('primary-menu')): ?>
            <nav class="mobile-menu-drawer__nav" aria-label="<?php esc_attr_e('Mobile Primary Menu', 'ha'); ?>">
                <?php wp_nav_menu(array(
                    'theme_location' => 'primary-menu',
                    'menu_class'     => 'mobile-menu__nav',
                    'container'      => false,
                )); ?>
            </nav>
        <?php endif; ?>
    </div>

    <div class="mobile-menu-drawer__footer">
        <a class="ha-button w-100 justify-content-center">
            Book Now
            <?php echo ha_svg_icon('arrow_next'); ?>
        </a>
    </div>
</aside>