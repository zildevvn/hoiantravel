<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="2mpfUMJmwRUQF5ZE9UEn7dZiskd0XSM3HiLv_0UxQc4" />
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Tailwind CSS CDN with forms and container queries plugins -->
    <!-- <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind Configuration for bespoke fonts and luxury palette -->
    <!-- <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'serif'],
                        playfair: ['"Playfair Display"', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        heritage: {
                            charcoal: '#241C1A',
                            terracotta: '#C24A2D',
                            gold: '#DFA128',
                            creme: '#FBF9F5',
                            pine: '#1E4D48',
                        }
                    }
                }
            }
        }
    </script> -->
    <?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php do_action('ha_hook_header'); ?>
    <?php do_action('ha_hook_modal_search'); ?>