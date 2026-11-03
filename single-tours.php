<?php
/**
 * The template for displaying single posts
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package     hle
 */

get_header();
?>
<?php get_template_part('template-parts/single-tour/header-bar'); ?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/single-tour/hero-section'); ?>
    <?php get_template_part('template-parts/single-tour/gallery-section'); ?>
    <div id="ha-tour-options-container" data-post-id="<?php echo get_the_ID(); ?>"></div>
    <?php get_template_part('template-parts/single-tour/main-section'); ?>
    <?php get_template_part('template-parts/single-tour/related-tour'); ?>
</main>

<?php
get_footer();