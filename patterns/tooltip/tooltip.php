<?php
/**
 * Title: Alert Info
 * Description: Alert component for displaying informational messages.
 * Categories: components, status
 * Keywords: alert, info, information, message
 * Slug: wp-site-core/component/alert-info
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$label   = $args['label'] ?? 'Information';
$text    = $args['text'] ?? '';
$url     = $args['url'] ?? '';
$element = $args['element'] ?? ($url !== '' ? 'a' : 'button');

// 2. Validation of input parameters
if (!in_array($element, ['button', 'a', 'span'], true)) {
    _doing_it_wrong('component/tooltip', 'element must be button, a or span.', '1.0.0');
    $element = $url !== '' ? 'a' : 'button';
}
$inline = !empty($args['inline']);

// 3. Generate unique ID for the tooltip
$id = wp_unique_id('tooltip-');
?>
<span class="tooltip<?php echo $inline ? ' tooltip--inline' : ''; ?>" data-floating="tooltip">
    <?php if ($element === 'a') : ?>
        <?php if ($url === '') : ?><?php _doing_it_wrong('component/tooltip', 'A link trigger requires a URL.', '1.0.0'); ?><?php endif; ?>
        <a href="<?php echo esc_url($url); ?>" class="tooltip__trigger" data-floating-trigger aria-describedby="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></a>
    <?php elseif ($element === 'span') : ?>
        <span class="tooltip__trigger" tabindex="0" data-floating-trigger aria-describedby="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></span>
    <?php else : ?>
        <button type="button" class="<?php echo $inline ? 'tooltip__trigger' : 'btn btn--ghost'; ?>" data-floating-trigger aria-describedby="<?php echo esc_attr($id); ?>"><?php echo esc_html($label); ?></button>
    <?php endif; ?>
    <span id="<?php echo esc_attr($id); ?>" class="tooltip__panel" role="tooltip" popover="manual" data-floating-panel><?php echo esc_html($text); ?></span>
</span>
