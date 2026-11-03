<?php
/**
 * Template part for displaying a search modal
 *
 * @package vm
 */
?>
<!-- Search Modal -->
<div id="search-modal" class="search-modal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="search-modal__overlay"></div>
    <button class="search-modal__close" aria-label="<?php esc_attr_e('Close search', 'vm'); ?>" type="button">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
    <div class="search-modal__content">
        <div class="search-modal__inner">
            <form role="search" method="get" class="search-modal__form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="search-modal__field-wrapper">
                    <label for="search-modal-input" class="visually-hidden"><?php _e('Search for:', 'vm'); ?></label>
                    <input type="search" id="search-modal-input" class="search-modal__input"
                        placeholder="<?php esc_attr_e('Search tours, destinations...', 'vm'); ?>"
                        value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
                    <button type="submit" class="search-modal__submit" aria-label="<?php esc_attr_e('Submit search', 'vm'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 17L21 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path
                                d="M3 11C3 15.4183 6.58172 19 11 19C13.213 19 15.2161 18.1015 16.6644 16.6493C18.1077 15.2022 19 13.2053 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </button>
                </div>
                <span class="search-modal__info"><?php _e('Press Enter to search or ESC to close.', 'vm'); ?></span>
            </form>
        </div>
    </div>
</div>