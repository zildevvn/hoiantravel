<?php
/**
 * Global Back to Top Button
 *
 * @package hoiantravel
 */
?>
<button type="button" 
        id="ha-back-to-top" 
        class="ha-back-to-top js-back-to-top" 
        aria-label="<?php esc_attr_e('Back to top', 'hoiantravel'); ?>"
        title="<?php esc_attr_e('Back to top', 'hoiantravel'); ?>">
    <span class="ha-back-to-top__icon" aria-hidden="true">
        <?= ha_svg_icon('arrow-up'); ?>
    </span>
    <span class="ha-back-to-top__tooltip" aria-hidden="true">
        <?php esc_html_e('Top', 'hoiantravel'); ?>
    </span>
</button>

