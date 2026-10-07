<?php
/**
 * Title: Modal Trigger
 * Description: Modal trigger component for opening modal dialogs.
 * Categories: components, status
 * Keywords: modal, trigger, dialog, popup
 * Slug: wp-site-core/component/modal-trigger
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args     = $args ?? [];
$modal_id = sanitize_html_class($args['modal_id'] ?? '');
$label    = $args['label'] ?? 'Ouvrir la fenêtre';
$element  = $args['element'] ?? 'a';
$type     = $args['type'] ?? 'primary';
$url      = $args['url'] ?? '';

// 2. Validation of input parameters
$allowed_types = ['primary', 'secondary', 'outline', 'ghost', 'success', 'warning', 'danger', 'info'];

if ($modal_id === '') {
    return;
}

if (!in_array($element, ['a', 'button'], true)) {
    $element = 'a';
}

// 3. Prepare CSS classes for the trigger element
if (!in_array($type, $allowed_types, true)) {
    $type = 'primary';
}

$classes = 'btn btn--' . $type;
?>
<?php if ($element === 'button') : ?>
    <button
        type="button"
        class="<?php echo esc_attr($classes); ?>"
        aria-haspopup="dialog"
        aria-controls="<?php echo esc_attr($modal_id); ?>"
        data-modal-open="<?php echo esc_attr($modal_id); ?>"
    >
        <?php echo esc_html($label); ?>
    </button>
<?php else : ?>
    <a
        href="<?php echo esc_url($url ?: '#' . $modal_id); ?>"
        class="<?php echo esc_attr($classes); ?>"
        aria-haspopup="dialog"
        aria-controls="<?php echo esc_attr($modal_id); ?>"
        data-modal-open="<?php echo esc_attr($modal_id); ?>"
    >
        <?php echo esc_html($label); ?>
    </a>
<?php endif; ?>
