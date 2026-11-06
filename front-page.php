<?php
/**
 * Template Name: Homepage
 * Front Page Template
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/home/hero-section'); ?>
    <?php get_template_part('template-parts/home/trust-proof-section'); ?>
    <?php get_template_part('template-parts/home/destination-section'); ?>
    <?php get_template_part('template-parts/home/about-section'); ?>
    <?php get_template_part('template-parts/home/tours-section'); ?>
    <?php get_template_part('template-parts/home/cars-section'); ?>
</main>
<?php
get_footer();