<?php

/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package sazukaru
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/tour-locations/hero-section'); ?>
    <?php get_template_part('template-parts/tour-locations/about-section'); ?>
    <?php get_template_part('template-parts/tour-locations/grid-tour-section'); ?>
    <?php get_template_part('template-parts/tour-locations/best-time-section'); ?>
    <?php get_template_part('template-parts/tour-locations/faqs-section'); ?>
</main>
<?php
get_footer();
