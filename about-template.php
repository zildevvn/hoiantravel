<?php
/**
 * Template Name: About
 */
get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/about/hero-section'); ?>
    <?php get_template_part('template-parts/about/intro-section'); ?>
    <?php get_template_part('template-parts/about/counters-section'); ?>
    <?php get_template_part('template-parts/about/about-section'); ?>
    <?php get_template_part('template-parts/about/steps-booking-section'); ?>
    <?php get_template_part('template-parts/about/our-team-section'); ?>
</main>
<?php get_footer(); ?>