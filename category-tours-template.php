<?php
/**
 * Template Name: Category Tours
 */
get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/customized-tours/hero-section'); ?>
    <?php get_template_part('template-parts/category-tours/about-section'); ?>
    <?php get_template_part('template-parts/category-tours/grid-tours-section'); ?>
    <?php get_template_part('template-parts/category-tours/best-time-section'); ?>
    <?php get_template_part('template-parts/category-tours/faqs-section'); ?>
</main>
<?php get_footer(); ?>