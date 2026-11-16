<?php
/**
 * The template for displaying single cars
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package hoiantravel
 */

get_header();
?>

<main id="primary" class="site-main single-car-page">
    <?php get_template_part('template-parts/single-car/hero-section'); ?>
    <?php get_template_part('template-parts/single-car/main-section'); ?>
    <div id="ha-car-options-container" data-post-id="<?php echo get_the_ID(); ?>"></div>
    <?php get_template_part('template-parts/single-car/related-cars'); ?>
</main>

<?php
get_footer();

