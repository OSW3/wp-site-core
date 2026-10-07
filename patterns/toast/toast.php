<?php
/**
 * Title: Toast Notification
 * Description: Toast notification component for displaying brief messages to the user.
 * Categories: components, status
 * Keywords: alert, info, information, message
 * Slug: wp-site-core/component/toast
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];
$id = $args['toast_id'] ?? wp_unique_id('toast-');
$title = $args['title'] ?? '';
$content = $args['content'] ?? '';
$type = $args['type'] ?? 'info';
$position = $args['position'] ?? 'bottom-right';
$duration = $args['duration'] ?? 5000;
$auto_show = !empty($args['auto_show']);

// 2. Validation of input parameters
if (!is_string($id) || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $id) || !is_string($title) || !is_string($content) || trim($content) === '') {
    _doing_it_wrong('component/toast', 'Provide a valid toast_id, a string title and non-empty HTML content.', '1.0.0');
    return;
}
if (!in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning'], true)
    || !in_array($position, ['top-left', 'top-right', 'bottom-left', 'bottom-right'], true)
    || !is_numeric($duration) || !is_finite((float) $duration) || (float) $duration < 0 || (float) $duration > 2147483647) {
    _doing_it_wrong('component/toast', 'Invalid type, position or duration (0 to 2147483647 ms).', '1.0.0');
    return;
}
?>
<div id="<?php echo esc_attr($id); ?>" class="toast toast--<?php echo esc_attr($type); ?> toast--<?php echo esc_attr($position); ?>" data-toast data-duration="<?php echo esc_attr($duration); ?>" data-auto-show="<?php echo $auto_show ? 'true' : 'false'; ?>" role="group" aria-label="<?php echo esc_attr($title ?: __('Notification', 'wp-site-core')); ?>">
    <div class="toast__body">
        <?php if ($title !== '') : ?><strong class="toast__title"><?php echo esc_html($title); ?></strong><?php endif; ?>
        <div class="toast__content"><?php echo wp_kses_post($content); ?></div>
    </div>
    <button type="button" class="toast__close" data-toast-dismiss hidden aria-label="<?php echo esc_attr__('Fermer la notification', 'wp-site-core'); ?>">&times;</button>
</div>
