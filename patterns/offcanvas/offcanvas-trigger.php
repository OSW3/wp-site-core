<?php
/**
 * Title: Offcanvas Trigger
 * Description: Offcanvas trigger component for opening offcanvas panels.
 * Categories: components, status
 * Keywords: offcanvas, trigger, side panel, sidebar, slide-in
 * Slug: wp-site-core/component/offcanvas-trigger
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$id      = $args['offcanvas_id'] ?? '';
$label   = $args['label'] ?? __('Ouvrir le panneau', 'wp-theme-test');
$element = $args['element'] ?? 'button';

// 2. Validation of input parameters
$type = $args['type'] ?? 'primary';
if (!is_string($id) || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $id)
    || !is_string($label) || trim($label) === ''
    || !in_array($element, ['button', 'a', 'span'], true)
    || !in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'], true)) {
    _doing_it_wrong('component/offcanvas-trigger', 'Provide offcanvas_id, label, a valid element and button type.', '1.0.0');
    return;
}
?>
<<?php echo $element; ?> class="btn btn--<?php echo esc_attr($type); ?>" data-offcanvas-open="<?php echo esc_attr($id); ?>" aria-haspopup="dialog" aria-controls="<?php echo esc_attr($id); ?>" aria-expanded="false"
    <?php if ($element === 'button') : ?>type="button"<?php elseif ($element === 'a') : ?>href="#<?php echo esc_attr($id); ?>" role="button"<?php else : ?>role="button" tabindex="0"<?php endif; ?>
><?php echo esc_html($label); ?></<?php echo $element; ?>>
