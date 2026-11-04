<?php
/**
 * Template part for displaying a search modal
 *
 * @package ha
 */
?>
<!-- Search Modal -->
<div id="search-modal" class="search-modal" aria-hidden="true" role="dialog" aria-modal="true"
    aria-labelledby="search-modal-title">
    <div class="search-modal__overlay" tabindex="-1"></div>

    <div class="search-modal__dialog">
        <button id="btn_close_search" class="search-modal__close d-flex align-items-center justify-content-center"
            aria-label="<?php esc_attr_e('Close search', 'ha'); ?>" type="button">
            <?php echo ha_svg_icon('menu-close-icon'); ?>
        </button>

        <div class="search-modal__content">
            <div class="search-modal__header text-center">
                <span class="search-modal__badge"><?php esc_html_e('Hoi An Travel Search', 'ha'); ?></span>
                <h2 id="search-modal-title" class="search-modal__title">
                    
                    <?php esc_html_e('What are you looking for?', 'ha'); ?></h2>
                <p class="search-modal__subtitle"><?php esc_html_e('Discover your next experience in Hoi An.', 'ha'); ?>
                </p>
            </div>

            <form role="search" method="get" class="search-modal__form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-modal__field-wrapper">
                    <span class="search-modal__input-icon" aria-hidden="true">
                        <?php echo ha_svg_icon('search-icon'); ?>
                    </span>
                    <label for="search-modal-input"
                        class="visually-hidden"><?php esc_html_e('Search for:', 'ha'); ?></label>
                    <input type="search" id="search-modal-input" class="search-modal__input"
                        placeholder="<?php esc_attr_e('Search tours, destinations, activities...', 'ha'); ?>"
                        value="<?php echo esc_attr(get_search_query()); ?>" name="s" autocomplete="off" required />
                    <button type="submit" class="ha-button search-modal__submit"
                        aria-label="<?php esc_attr_e('Submit search', 'ha'); ?>">
                        <span><?php esc_html_e('Search', 'ha'); ?></span>
                        <?php echo ha_svg_icon('arrow_next'); ?>
                    </button>
                </div>
                <div class="search-modal__footer text-center">
                    <span
                        class="search-modal__info"><?php esc_html_e('Press Enter to search or ESC to close.', 'ha'); ?></span>
                </div>
            </form>
        </div>
    </div>
</div>