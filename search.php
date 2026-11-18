<?php
/**
 * The template for displaying search results pages
 *
 * Design Direction: Heritage Editorial + Modern Travel Discovery
 * Preserves WordPress native search query, get_search_query(), and ha_pagination().
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 * @package hoiantravel
 */

get_header();

global $wp_query;
$search_query  = trim(get_search_query());
$total_results = isset($wp_query->found_posts) ? (int) $wp_query->found_posts : 0;
$bg_banner     = function_exists('get_field') ? get_field('bg_banner', 'option') : '';
$has_image     = !empty($bg_banner);

$breadcrumb_label = !empty($search_query)
	? sprintf(__('Search: "%s"', 'hoiantravel'), $search_query)
	: __('Search', 'hoiantravel');
?>

<main id="primary" class="site-main search-page-main">
	<!-- 1. SEARCH HERO / EDITORIAL HEADER -->
	<section class="ha-section hero-section-shared search-hero-section<?= $has_image ? ' hero-section-shared--has-image' : ' hero-section-shared--no-image'; ?>">
		<div class="ha-section__bg hero-section-shared__bg">
			<?php if ($has_image): ?>
				<img src="<?= esc_url($bg_banner); ?>" alt="<?php esc_attr_e('Search Hoi An Travel experiences', 'hoiantravel'); ?>" loading="eager" />
			<?php else: ?>
				<div class="hero-section-shared__fallback-pattern" aria-hidden="true">
					<?= ha_svg_icon('about-watermark'); ?>
				</div>
			<?php endif; ?>
			<div class="hero-section-shared__overlay"></div>
			<div class="hero-section-shared__glow"></div>
		</div>

		<div class="container hero-section-shared__container">
			<div class="hero-section-shared__box search-hero-section__box">
				<?php if (function_exists('ha_breadcrumbs')): ?>
					<div class="hero-section-shared__breadcrumbs-wrap">
						<?php ha_breadcrumbs($breadcrumb_label); ?>
					</div>
				<?php endif; ?>

				<div class="search-hero-section__badge-wrap">
					<span class="ha-badge ha-badge--on-dark ha-badge--pill">
						<span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('search-icon'); ?></span>
						<span class="ha-badge__text"><?php esc_html_e('Travel Discovery', 'hoiantravel'); ?></span>
					</span>
				</div>

				<?php if (!empty($search_query)): ?>
					<h1 class="ha-heading hero-section-shared__title search-title">
						<?php esc_html_e('Search Results for', 'hoiantravel'); ?>
						<span>&ldquo;<?php echo esc_html($search_query); ?>&rdquo;</span>
					</h1>
					<?php if ($total_results > 0): ?>
						<p class="hero-section-shared__desc search-subtitle">
							<?php
							printf(
								/* translators: %s: total number of search results */
								_n(
									'We found <strong>%s</strong> matching result for your search.',
									'We found <strong>%s</strong> matching results for your search.',
									$total_results,
									'hoiantravel'
								),
								esc_html(number_format_i18n($total_results))
							);
							?>
						</p>
					<?php else: ?>
						<p class="hero-section-shared__desc search-subtitle">
							<?php esc_html_e("We couldn't find any exact matches for your keyword. Try refining your search below.", 'hoiantravel'); ?>
						</p>
					<?php endif; ?>
				<?php else: ?>
					<h1 class="ha-heading hero-section-shared__title search-title">
						<?php esc_html_e('Explore Hoi An Travel', 'hoiantravel'); ?>
					</h1>
					<p class="hero-section-shared__desc search-subtitle">
						<?php esc_html_e('Enter a keyword below to discover curated tours, private car transfers, and local travel guides.', 'hoiantravel'); ?>
					</p>
				<?php endif; ?>

				<!-- Primary Search Form (GET method preserved) -->
				<div class="search-form-wrapper">
					<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="modern-search-form">
						<div class="input-group">
							<label for="ha-search-page-input" class="visually-hidden">
								<?php esc_html_e('Search tours, cars, and travel guides', 'hoiantravel'); ?>
							</label>
							<span class="search-icon" aria-hidden="true">
								<?= ha_svg_icon('search-icon'); ?>
							</span>
							<input
								type="search"
								id="ha-search-page-input"
								name="s"
								placeholder="<?php esc_attr_e('Search tours, destinations, cars, or travel tips...', 'hoiantravel'); ?>"
								value="<?php echo esc_attr($search_query); ?>"
								autocomplete="off"
								required
							/>
							<button type="submit" class="ha-button ha-button--primary search-submit">
								<span><?php esc_html_e('Search', 'hoiantravel'); ?></span>
								<?= ha_svg_icon('arrow-right'); ?>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="hero-section-shared__ribbon" aria-hidden="true">
			<?= ha_svg_icon('indochine-ribbon'); ?>
		</div>
	</section>

	<!-- 2. SEARCH RESULTS & EMPTY STATE -->
	<section class="search-results ha-results-search-section" id="search-results" aria-label="<?php esc_attr_e('Search Results', 'hoiantravel'); ?>">
		<div class="container">
			<?php if (have_posts() && !empty($search_query)): ?>
				<?php
				$paged    = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
				$per_page = (int) get_query_var('posts_per_page');
				if ($per_page <= 0) {
					$per_page = (int) get_option('posts_per_page', 10);
				}

				$start = ($paged - 1) * $per_page + 1;
				$end   = min($paged * $per_page, $total_results);
				?>

				<!-- Editorial Results Toolbar -->
				<header class="ha-results-search-section__header">
					<div class="ha-results-search-section__summary">
						<span class="ha-results-search-section__indicator" aria-hidden="true"></span>
						<h2 class="ha-results-search-section__total">
							<?php if ($total_results > $per_page): ?>
								<?php
								printf(
									/* translators: 1: start index, 2: end index, 3: total results, 4: search query */
									esc_html__('Showing %1$s–%2$s of %3$s results for', 'hoiantravel'),
									'<strong>' . esc_html(number_format_i18n($start)) . '</strong>',
									'<strong>' . esc_html(number_format_i18n($end)) . '</strong>',
									'<strong>' . esc_html(number_format_i18n($total_results)) . '</strong>'
								);
								?>
								<span class="ha-results-search-section__query">&ldquo;<?php echo esc_html($search_query); ?>&rdquo;</span>
							<?php else: ?>
								<?php
								printf(
									/* translators: %s: total number of results */
									_n(
										'Showing %s result for',
										'Showing %s results for',
										$total_results,
										'hoiantravel'
									),
									'<strong>' . esc_html(number_format_i18n($total_results)) . '</strong>'
								);
								?>
								<span class="ha-results-search-section__query">&ldquo;<?php echo esc_html($search_query); ?>&rdquo;</span>
							<?php endif; ?>
						</h2>
					</div>

					<div class="ha-results-search-section__quick-links">
						<a href="<?php echo esc_url(home_url('/hoi-an-tours/')); ?>" class="ha-results-search-section__browse-link">
							<span><?php esc_html_e('Browse All Tours', 'hoiantravel'); ?></span>
							<?= ha_svg_icon('arrow-up-right'); ?>
						</a>
					</div>
				</header>

				<!-- Results Grid -->
				<div class="ha-results-search-section__list">
					<?php
					while (have_posts()):
						the_post();
						$post_type = get_post_type();

						if ($post_type === 'tours' && function_exists('ha_item_tour')) {
							ha_item_tour();
						} elseif ($post_type === 'cars' && function_exists('ha_item_car')) {
							ha_item_car();
						} elseif ($post_type === 'cars' && function_exists('ha_car_tour_item')) {
							ha_car_tour_item();
						} else {
							if (function_exists('ha_post_item')) {
								ha_post_item();
							}
						}
					endwhile;
					?>
				</div>

				<!-- Global Pagination -->
				<div class="ha-results-search-section__pagination">
					<?php
					if (function_exists('ha_pagination')) {
						ha_pagination();
					}
					wp_reset_postdata();
					?>
				</div>

			<?php else: ?>

				<!-- Empty Search Results State -->
				<div class="search-empty-state">
					<div class="search-empty-state__card">
						<div class="search-empty-state__content">
							<div class="search-empty-state__icon" aria-hidden="true">
								<?= ha_svg_icon('search-empty'); ?>
							</div>

							<?php if (!empty($search_query)): ?>
								<h2 class="ha-heading search-empty-state__title">
									<?php esc_html_e('No Matching Results Found', 'hoiantravel'); ?>
								</h2>
								<p class="search-empty-state__desc">
									<?php esc_html_e("We couldn't find any tours, transfers, or articles matching", 'hoiantravel'); ?>
									<span class="search-empty-state__keyword">&ldquo;<?php echo esc_html($search_query); ?>&rdquo;</span>.
								</p>
								<p class="search-empty-state__subdesc">
									<?php esc_html_e('Please check your spelling, try a broader destination keyword, or search again below.', 'hoiantravel'); ?>
								</p>
							<?php else: ?>
								<h2 class="ha-heading search-empty-state__title">
									<?php esc_html_e('Discover Hoi An & Central Vietnam', 'hoiantravel'); ?>
								</h2>
								<p class="search-empty-state__desc">
									<?php esc_html_e('Enter a keyword to search across our handcrafted tours, private car transfers, and local travel guides.', 'hoiantravel'); ?>
								</p>
							<?php endif; ?>

							<!-- Inline Retry Search Form inside Empty State -->
							<div class="search-empty-state__retry-form">
								<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="modern-search-form modern-search-form--light">
									<div class="input-group">
										<label for="ha-empty-search-input" class="visually-hidden">
											<?php esc_html_e('Try another search keyword', 'hoiantravel'); ?>
										</label>
										<span class="search-icon" aria-hidden="true">
											<?= ha_svg_icon('search-icon'); ?>
										</span>
										<input
											type="search"
											id="ha-empty-search-input"
											name="s"
											placeholder="<?php esc_attr_e('Try "Hoi An", "Hue", "My Son", "Coconut Boat"...', 'hoiantravel'); ?>"
											value=""
											autocomplete="off"
											required
										/>
										<button type="submit" class="ha-button ha-button--primary search-submit">
											<span><?php esc_html_e('Search Again', 'hoiantravel'); ?></span>
											<?= ha_svg_icon('arrow-right'); ?>
										</button>
									</div>
								</form>
							</div>

							<div class="search-empty-state__tips" aria-label="<?php esc_attr_e('Search suggestions', 'hoiantravel'); ?>">
								<div class="search-empty-state__tip-card">
									<?= ha_svg_icon('check-simple'); ?>
									<span><?php esc_html_e('Check your spelling', 'hoiantravel'); ?></span>
								</div>
								<div class="search-empty-state__tip-card">
									<?= ha_svg_icon('check-simple'); ?>
									<span><?php esc_html_e('Use shorter keywords', 'hoiantravel'); ?></span>
								</div>
								<div class="search-empty-state__tip-card">
									<?= ha_svg_icon('check-simple'); ?>
									<span><?php esc_html_e('Search by destination', 'hoiantravel'); ?></span>
								</div>
								<div class="search-empty-state__tip-card">
									<?= ha_svg_icon('check-simple'); ?>
									<span><?php esc_html_e('Browse popular tours', 'hoiantravel'); ?></span>
								</div>
							</div>

							<div class="search-empty-state__actions">
								<a href="<?php echo esc_url(home_url('/hoi-an-tours/')); ?>" class="ha-button">
									<?= ha_svg_icon('feature-compass'); ?>
									<span><?php esc_html_e('Explore All Tours', 'hoiantravel'); ?></span>
								</a>
								<a href="<?php echo esc_url(home_url('/')); ?>" class="ha-button search-empty-state__btn-secondary">
									<?= ha_svg_icon('arrow-left'); ?>
									<span><?php esc_html_e('Back to Home', 'hoiantravel'); ?></span>
								</a>
							</div>
						</div>
					</div>

					<?php
					$popular_tours = new WP_Query([
						'post_type'      => 'tours',
						'posts_per_page' => 3,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					]);
					if ($popular_tours->have_posts()):
						?>
						<div class="search-recommendations">
							<header class="search-recommendations__header">
								<span class="ha-badge">
									<span class="ha-badge__icon" aria-hidden="true"><?= ha_svg_icon('feature-lantern'); ?></span>
									<span class="ha-badge__text"><?php esc_html_e('Curated Experiences', 'hoiantravel'); ?></span>
								</span>
								<h3 class="ha-heading search-recommendations__title">
									<?php esc_html_e('You Might Also Like', 'hoiantravel'); ?>
								</h3>
							</header>

							<div class="ha-results-search-section__list">
								<?php
								while ($popular_tours->have_posts()):
									$popular_tours->the_post();
									if (function_exists('ha_item_tour')) {
										ha_item_tour();
									}
								endwhile;
								wp_reset_postdata();
								?>
							</div>
						</div>
					<?php endif; ?>
				</div>

			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
