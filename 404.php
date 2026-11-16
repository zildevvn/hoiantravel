<?php
/**
 * The template for displaying 404 pages (not found)
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 * @package ha
 */

get_header();

$cta_group = [
    'cta' => [
        'url' => home_url(),
        'title' => 'Go back to Home',
        'target' => '_self'
    ],
    'cta_style' => 'primary'
];
?>
<main class="site-main">
    <section class="error-404-section ha-section">
        <div class="container">
            <div class="error-404-section__code">404</div>
            <h1 class="error-404-section__title">Oops! Page Not Found</h1>
            <p class="error-404-section__desc">The page you are looking for might have been removed, had its name
                changed, or is temporarily unavailable. Let's get you back on track.</p>

            <div class="search-form-wrapper">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
                    class="modern-search-form">
                    <div class="input-group">
                        <?= ha_svg_icon('search-icon', 'search-icon'); ?>
                        <input type="search" name="s" placeholder="Search for tours, cars, or articles..." required />
                        <button type="submit" class="ha-button ha-button--primary search-submit">Search</button>
                    </div>
                </form>
            </div>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="ha-button ha-button--primary back-home-btn">
                <?= ha_svg_icon('arrow-left'); ?>
                Back to Home
            </a>
        </div>
    </section>
</main><!-- #main -->
<?php
get_footer();