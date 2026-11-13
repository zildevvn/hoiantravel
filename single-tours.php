<?php
/**
 * The template for displaying single tours
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package hoiantravel
 */

get_header();
?>

<main id="primary" class="site-main single-tour-page">
    <?php get_template_part('template-parts/single-tour/hero-section'); ?>
    <?php get_template_part('template-parts/single-tour/gallery-section'); ?>
    <?php get_template_part('template-parts/single-tour/main-section'); ?>
    <div id="ha-tour-options-container" data-post-id="<?php echo get_the_ID(); ?>"></div>
    <?php get_template_part('template-parts/single-tour/related-tour'); ?>
</main>

<?php
get_footer();
