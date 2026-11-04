<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="2mpfUMJmwRUQF5ZE9UEn7dZiskd0XSM3HiLv_0UxQc4" />
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body id="body" <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <?php do_action('ha_hook_header'); ?>
    <?php do_action('ha_hook_modal_search'); ?>