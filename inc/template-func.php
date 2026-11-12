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


function ha_item_car()
{
    $location = get_field('location_car');
    $time = get_field('time_car');
    $price = get_field('price_car');
    $pick_up = get_field('pick_up_car');
    $currency = get_field('currency', 'option');
    $route_display = !empty($location) ? preg_replace('/\s*[-–—]\s*/u', ' ➔ ', $location) : '';
    ?>
    <a href="<?= the_permalink(); ?>" class="car-card" aria-label="read more <?php the_title() ?>">

        <div>
            <div class="car-card__media">
                <img src="<?= get_the_post_thumbnail_url(); ?>" alt="image for <?= the_title(); ?>" width="380"
                    height="260" />

                <div class="car-card__duration">
                    <?= $time; ?>
                </div>
            </div>

            <?php if (!empty($route_display)): ?>
                <div class="car-card__route-wrap">
                    <span class="car-card__route"><?= esc_html($route_display); ?></span>
                </div>
            <?php endif; ?>

            <h3 class="car-card__title h5">
                <?php the_title() ?>
            </h3>

            <div class="car-card__specs">
                <div class="car-card__spec-item" title="Modern Fleet (Sedan, SUV &amp; Van)">
                    <?= ha_svg_icon('car'); ?>
                    <span>Modern Fleet (Sedan, SUV &amp; Van)</span>
                </div>
                <div class="car-card__spec-item"
                    title="<?= !empty($pick_up) ? esc_attr($pick_up) : 'Door-to-Door Service'; ?>">
                    <?= ha_svg_icon('door'); ?>
                    <span><?= !empty($pick_up) ? esc_html($pick_up) : 'Door-to-Door Service'; ?></span>
                </div>
                <div class="car-card__spec-item" title="English-Speaking Driver">
                    <?= ha_svg_icon('guide'); ?>
                    <span>English-Speaking Driver</span>
                </div>
                <div class="car-card__spec-item" title="Free Cold Water &amp; Wi-Fi">
                    <?= ha_svg_icon('wifi'); ?>
                    <span>Free Cold Water &amp; Wi-Fi</span>
                </div>
            </div>
        </div>

        <div class="car-card__footer">
            <div class="car-card__price-box">
                <span class="car-card__price-label">From</span>
                <span class="car-card__price-val">
                    <?= esc_html(ha_format_price($price, $currency)); ?>
                </span>
            </div>
            <button class="ha-button" type="button">

                Book Car
                <?= ha_svg_icon('arrow-right'); ?>
            </button>
        </div>
    </a>
<?php }

function ha_item_post($post_id = null)
{
    $post_id = $post_id ? $post_id : get_the_ID();
    if (!$post_id) {
        return;
    }

    $permalink = get_permalink($post_id);
    $title = get_the_title($post_id);

    $thumb_url = has_post_thumbnail($post_id)
        ? get_the_post_thumbnail_url($post_id, 'large')
        : '';

    if (empty($thumb_url)) {
        $thumb_url = 'https://lh3.googleusercontent.com/aida/AEtjO1XOa-2n3kfHFEN18NvbixXY_CUXbTZlchtFKuZ-Ja-QQ9mUlW2KQxBaqTTzcly6J6Kv57UarQeCKZRgofffJ957OZjFM3TMVs_1j37Mm1oaIjqFNoaTOfnd_CXU_znxbClN4kyX5ukJCniJpfQu3QFFgkldP4Crk4CxGZWb5phrVKjGnJ7JRoCTV8uTpRJlt6A6ZrfqlH3-y-qfPv8v6SAa50PlKEajY7gKOfJkrQ';
    }

    $categories = get_the_category($post_id);
    $category = (!empty($categories) && !is_wp_error($categories)) ? $categories[0] : null;
    $cat_name = $category ? $category->name : __('Travel Insights', 'hoiantravel');
    $cat_link = $category ? get_category_link($category->term_id) : '#';

    $date_formatted = get_the_date('M j, Y', $post_id);
    $date_iso = get_the_date('c', $post_id);

    $author_id = get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id) ?: __('Hoi An Insider', 'hoiantravel');
    $author_link = get_author_posts_url($author_id);
    ?>
    <article <?php post_class('ha-post-card', $post_id); ?>>
        <div class="ha-post-card__media-wrap">
            <a href="<?= esc_url($permalink); ?>" class="ha-post-card__media" tabindex="-1" aria-hidden="true">
                <img src="<?= esc_url($thumb_url); ?>" alt="<?= esc_attr($title); ?>" loading="lazy" width="400"
                    height="250" />
                <span class="ha-post-card__overlay" aria-hidden="true"></span>
            </a>

            <?php if (!empty($cat_name)): ?>
                <a href="<?= esc_url($cat_link); ?>" class="ha-post-card__badge">
                    <?= esc_html($cat_name); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="ha-post-card__body">
            <h3 class="ha-post-card__title h5">
                <a href="<?= esc_url($permalink); ?>" title="<?= esc_attr($title); ?>">
                    <?= esc_html($title); ?>
                </a>
            </h3>

            <div class="ha-post-card__divider" aria-hidden="true"></div>

            <div class="ha-post-card__meta">
                <div class="ha-post-card__meta-item ha-post-card__meta-item--date">
                    <span class="ha-post-card__meta-icon" aria-hidden="true">
                        <?= ha_svg_icon('calendar'); ?>
                    </span>
                    <time datetime="<?= esc_attr($date_iso); ?>" class="ha-post-card__meta-text">
                        <?= esc_html($date_formatted); ?>
                    </time>
                </div>

                <span class="ha-post-card__meta-separator" aria-hidden="true"></span>

                <div class="ha-post-card__meta-item ha-post-card__meta-item--author">
                    <span class="ha-post-card__meta-icon" aria-hidden="true">
                        <?= ha_svg_icon('user'); ?>
                    </span>
                    <a href="<?= esc_url($author_link); ?>" class="ha-post-card__meta-text">
                        <?= esc_html($author_name); ?>
                    </a>
                </div>
            </div>

            <div class="ha-post-card__action">
                <a href="<?= esc_url($permalink); ?>" class="ha-post-card__read-more ha-button"
                    aria-label="<?= esc_attr(sprintf(__('Read more about %s', 'hoiantravel'), $title)); ?>">
                    <?= esc_html__('Read More', 'hoiantravel'); ?>
                    <?= ha_svg_icon('arrow-right'); ?>
                </a>
            </div>
        </div>
    </article>
    <?php
}

if (!function_exists('ha_post_item')) {
    function ha_post_item($post_id = null)
    {
        ha_item_post($post_id);
    }
}