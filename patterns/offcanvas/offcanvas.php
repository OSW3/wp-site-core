<?php
/**
 * Title: Offcanvas
 * Description: Offcanvas component for displaying side panels.
 * Categories: components, status
 * Keywords: offcanvas, side panel, sidebar, slide-in
 * Slug: wp-site-core/component/offcanvas
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args        = $args ?? [];
$id          = $args['offcanvas_id'] ?? wp_unique_id('offcanvas-');
$title       = $args['title'] ?? __('Menu', 'wp-theme-test');
$content     = $args['content'] ?? '';
$position    = $args['position'] ?? 'left';
$close_label = $args['close_label'] ?? __('Fermer le panneau', 'wp-theme-test');

// 2. Validation of input parameters
if (!is_string($id) || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $id)
    || !is_string($title) || trim($title) === '' || !is_string($content)
    || !is_string($close_label) || trim($close_label) === ''
    || !in_array($position, ['left', 'right'], true)) {
    _doing_it_wrong('component/offcanvas', 'Provide a valid offcanvas_id, title, content, close_label and left/right position.', '1.0.0');
    return;
}
?>
<dialog id="<?php echo esc_attr($id); ?>" class="offcanvas offcanvas--<?php echo esc_attr($position); ?>" aria-labelledby="<?php echo esc_attr($id); ?>-title" data-offcanvas>
    <div class="offcanvas__header">
        <h2 id="<?php echo esc_attr($id); ?>-title" class="offcanvas__title"><?php echo esc_html($title); ?></h2>
        <button type="button" class="offcanvas__close" data-offcanvas-close aria-label="<?php echo esc_attr($close_label); ?>" autofocus><span aria-hidden="true">&times;</span></button>
    </div>
    <div class="offcanvas__content"><?php echo wp_kses_post($content); ?></div>
</dialog>
