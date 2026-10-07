<?php
/**
 * Title: Toast Trigger
 * Description: Trigger element for displaying toast notifications.
 * Categories: components, status
 * Keywords: alert, info, information, message
 * Slug: wp-site-core/component/toast-trigger
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$id      = $args['toast_id'] ?? '';
$label   = $args['label'] ?? __('Afficher la notification', 'wp-site-core');
$element = $args['element'] ?? 'button';
$type    = $args['type'] ?? 'primary';

// 2. Validation of input parameters
if (!is_string($id) || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $id) || !is_string($label) || trim($label) === ''
    || !in_array($element, ['button', 'a', 'span'], true)
    || !in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'], true)) {
    _doing_it_wrong('component/toast-trigger', 'Provide toast_id, label, a valid element and button type.', '1.0.0');
    return;
}
?>
<<?php echo $element; ?> class="btn btn--<?php echo esc_attr($type); ?>" data-toast-target="<?php echo esc_attr($id); ?>" aria-controls="<?php echo esc_attr($id); ?>"
    <?php if ($element === 'button') : ?>type="button"<?php elseif ($element === 'a') : ?>href="#<?php echo esc_attr($id); ?>" role="button"<?php else : ?>role="button" tabindex="0"<?php endif; ?>
><?php echo esc_html($label); ?></<?php echo $element; ?>>
