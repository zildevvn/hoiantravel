<?php
/**
 * Template Name: Tours
 */
get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/tours/hero-section'); ?>
    <?php
    get_template_part('template-parts/tours/tours-list');
    get_template_part('template-parts/tours/about-section');
    get_template_part('template-parts/tours/faqs-section');
    ?>
</main>
<?php get_footer(); ?>