<?php
$title = get_the_title();
$image = get_the_post_thumbnail_url();
if (!$image) {
    // fallback image
    $image = get_template_directory_uri() . '/assets/images/default-tour-bg.jpg';
}

$location_tour = get_field('location_tour');
$time_tour = get_field('time_tour');
$price_tour = get_field('price_tour');
$paxs_tours = get_field('paxs_tours');
$min_pax = isset($paxs_tours['min']) ? $paxs_tours['min'] : '';
$max_pax = isset($paxs_tours['max']) ? $paxs_tours['max'] : '';
$features = get_field('features_tour');

// Taxonomy for Type/Badge (assume 'tour_category' or similar, we'll use a generic placeholder or try to fetch it if exists)
$tour_types = get_the_terms(get_the_ID(), 'tour_cats');
$tour_type_name = ($tour_types && !is_wp_error($tour_types)) ? $tour_types[0]->name : 'Day Trip';
?>
<section class="hero-section" style="background-image: url('<?php echo esc_url($image); ?>');">
    <div class="hero-section-overlay"></div>
    <div class="container">
        <div class="hero-section-content">

            <?php vm_breadcrumbs($title); ?>

            <span class="hero-section__badge">
                <?php echo esc_html($tour_type_name); ?>
            </span>

            <h1 class="hero-section__title"><?php echo esc_html($title); ?></h1>

            <?php if (has_excerpt()): ?>
                <div class="hero-section__excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>

            <div class="hero-section__metadata d-flex align-items-center">
                <?php if ($time_tour): ?>
                    <div class="meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-clock-check-icon lucide-clock-check">
                            <path d="M12 6v6l4 2"></path>
                            <path d="M22 12a10 10 0 1 0-11 9.95"></path>
                            <path d="m22 16-5.5 5.5L14 19"></path>
                        </svg>
                        <span><?php echo esc_html($time_tour); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($min_pax && $max_pax): ?>
                    <div class="meta-item">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span><?php echo esc_html($min_pax) . ' - ' . esc_html($max_pax); ?> guests</span>
                    </div>
                <?php endif; ?>

                <?php if ($price_tour): ?>
                    <div class="meta-item">
                        <span>From
                            <span class="mb-0">₫</span>
                            <?php echo number_format((float) $price_tour, 0); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($features)): ?>
                <div class="hero-section__features">
                    <?php foreach ($features as $feature): ?>
                        <span class="feature-badge">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-check-icon lucide-check-check">
                                <path d="M18 6 7 17l-5-5" />
                                <path d="m22 10-7.5 7.5L13 16" />
                            </svg>
                            <?= $feature['item'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (function_exists('kk_star_ratings')): ?>
                <div class="hero-section__ratings">
                    <?php echo kk_star_ratings(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>