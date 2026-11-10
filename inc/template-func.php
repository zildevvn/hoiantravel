<?php

function ha_breadcrumbs($title)
{
    ?>
    <nav class="ha-breadcrumbs d-inline-flex align-items-center mb-0"
        aria-label="<?php esc_attr_e('Breadcrumbs', 'hoiantravel'); ?>">
        <a href="<?= esc_url(home_url('/')); ?>" class="ha-breadcrumbs__link">
            <span class="ha-breadcrumbs__icon" aria-hidden="true"><?= ha_svg_icon('home'); ?></span>
            <span>Home</span>
        </a>
        <span class="ha-breadcrumbs__sep" aria-hidden="true">
            <?= ha_svg_icon('chevrons-right'); ?>
        </span>

        <?php if (is_singular('tours') || is_tax('tour_cats') || is_tax('tour_locations')): ?>
            <a href="<?= esc_url(home_url('/hoi-an-tours/')); ?>" class="ha-breadcrumbs__link">Tours</a>
            <span class="ha-breadcrumbs__sep" aria-hidden="true">
                <?= ha_svg_icon('chevrons-right'); ?>
            </span>
        <?php endif; ?>

        <?php if (is_singular('cars')): ?>
            <a href="<?= esc_url(home_url('/hoi-an-car-rental/')); ?>" class="ha-breadcrumbs__link">Cars</a>
            <span class="ha-breadcrumbs__sep" aria-hidden="true">
                <?= ha_svg_icon('chevrons-right'); ?>
            </span>
        <?php endif; ?>

        <?php if (is_singular('post') || is_category() || is_tag()): ?>
            <a href="<?= esc_url(home_url('/hoi-an-travel-guide/')); ?>" class="ha-breadcrumbs__link">Blog</a>
            <span class="ha-breadcrumbs__sep" aria-hidden="true">
                <?= ha_svg_icon('chevrons-right'); ?>
            </span>
        <?php endif; ?>

        <span class="ha-breadcrumbs__current"><?= esc_html($title); ?></span>
    </nav>
<?php }

function ha_hero_section_shared($title = '', $image = '', $desc = '', $alt = '')
{
    $has_image = !empty($image);
    ?>
    <section
        class="ha-section hero-section-shared<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
        <div class="ha-section__bg hero-section-shared__bg">
            <?php if ($has_image): ?>
                <img src="<?= esc_url($image); ?>" alt="<?= esc_attr($alt); ?>" loading="eager" />
            <?php else: ?>
                <div class="hero-section-shared__fallback-pattern" aria-hidden="true">
                    <?= ha_svg_icon('about-watermark'); ?>
                </div>
            <?php endif; ?>
            <div class="hero-section-shared__overlay"></div>
            <div class="hero-section-shared__glow"></div>
        </div>

        <div class="container hero-section-shared__container">
            <div class="hero-section-shared__box">
                <div class="hero-section-shared__breadcrumbs-wrap">
                    <?php ha_breadcrumbs($title); ?>
                </div>

                <h1 class="ha-heading hero-section-shared__title">
                    <?= esc_html($title); ?>
                </h1>

                <?php if (!empty($desc)): ?>
                    <div class="hero-section-shared__desc">
                        <?= wp_kses_post($desc); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_singular('post')): ?>
                    <div class="hero-section-shared__ratings">
                        <?php if (function_exists('kk_star_ratings')): ?>
                            <?= kk_star_ratings(); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-section-shared__ribbon" aria-hidden="true">
            <?= ha_svg_icon('indochine-ribbon'); ?>
        </div>
    </section>
<?php }

if (!function_exists('vm_breadcrumbs')) {
    function vm_breadcrumbs($title)
    {
        ha_breadcrumbs($title);
    }
}

if (!function_exists('vm_hero_section_shared')) {
    function vm_hero_section_shared($title = '', $image = '', $desc = '')
    {
        ha_hero_section_shared($title, $image, $desc);
    }
}


if (!function_exists('vm_breadcrumbs')) {
    function vm_breadcrumbs($title)
    {
        ha_breadcrumbs($title);
    }
}

if (!function_exists('vm_hero_section_shared')) {
    function vm_hero_section_shared($title = '', $image = '', $desc = '')
    {
        ha_hero_section_shared($title, $image, $desc);
    }
}

function ha_item_tour()
{
    $tour_cats = get_the_terms(get_the_ID(), 'tour_cats');
    $cat_name = (!empty($tour_cats) && !is_wp_error($tour_cats)) ? implode(', ', wp_list_pluck($tour_cats, 'name')) : '';

    $tour_locs = get_the_terms(get_the_ID(), 'tour_locations');
    $loc_name = (!empty($tour_locs) && !is_wp_error($tour_locs)) ? implode(', ', wp_list_pluck($tour_locs, 'name')) : '';
    $price = get_field('price_tour');
    $currency = function_exists('ha_get_currency') ? ha_get_currency() : get_field('currency', 'option');

    ?>
    <a href="<?= the_permalink(); ?>" class="tour-card" aria-label="read more <?php the_title() ?>">

        <div class="tour-card__media">
            <img src="<?= get_the_post_thumbnail_url(); ?>" alt="image for <?= the_title(); ?>" width="380" height="260" />

            <?php if (!empty($cat_name)): ?>
                <div class="tour-card__duration"><?= esc_html($cat_name); ?></div>
            <?php endif; ?>
        </div>

        <?php
        // Rating data: same post meta source as kk-star-ratings (count_default / ratings_default),
        // falling back to the legacy keys overridden in inc/hooks.php.
        $kk_count = (int) get_post_meta(get_the_ID(), '_kksr_count_default', true);
        $kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings_default', true);
        if ($kk_count <= 0) {
            $kk_count = (int) get_post_meta(get_the_ID(), '_kksr_casts', true);
            $kk_ratings = (float) get_post_meta(get_the_ID(), '_kksr_ratings', true);
        }
        $kk_score = $kk_count > 0 ? round(min($kk_ratings / $kk_count, 5), 1, PHP_ROUND_HALF_DOWN) : 0;
        ?>
        <div class="tour-card__body">
            <div>
                <div class="tour-card__meta">
                    <span class="tour-card__location">
                        <?php if (!empty($loc_name)): ?>
                            <?php echo ha_svg_icon('location'); ?>
                            <span class="tour-card__location-text"><?= esc_html($loc_name); ?></span>
                        <?php endif; ?>
                    </span>
                    <?php if ($kk_count > 0): ?>
                        <span class="tour-card__rating">
                            <span class="tour-card__rating-star" aria-hidden="true">★</span>
                            <span class="tour-card__rating-score"><?= esc_html($kk_score); ?>/5</span>
                            <span class="tour-card__rating-count">(<?= esc_html(number_format_i18n($kk_count)); ?>)</span>
                        </span>
                    <?php endif; ?>
                </div>
                <h3 class="tour-card__title h5">
                    <?php the_title() ?>
                </h3>
                <div class="tour-card__highlights">
                    <span class="tour-card__highlight-item">
                        <?php echo ha_svg_icon('guide'); ?>
                        <span>English Guide</span>
                    </span>
                    <span class="tour-card__highlight-item">
                        <?php echo ha_svg_icon('pickup'); ?>
                        <span>Pickup Incl.</span>
                    </span>
                </div>
            </div>

            <div class="tour-card__footer">
                <div class="tour-card__price-box">
                    <span class="tour-card__price-label">From</span>
                    <span class="tour-card__price-val"><?= esc_html(ha_format_price($price, $currency)); ?></span>
                </div>
                <button class="ha-button" type="button">
                    Book Tour
                    <?php echo ha_svg_icon('arrow_next'); ?>
                </button>
            </div>
        </div>
    </a>
    <?php
}

if (!function_exists('vm_item_tour')) {
    function vm_item_tour($wrapper_class = '')
    {
        ha_item_tour();
    }
}