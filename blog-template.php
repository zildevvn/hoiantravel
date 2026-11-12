<?php
/**
 * Template Name: Blog
 */
get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/blog/hero-section'); ?>
    <?php get_template_part('template-parts/blog/about-section'); ?>
    <?php get_template_part('template-parts/blog/main-section'); ?>
</main>
<?php get_footer(); ?>