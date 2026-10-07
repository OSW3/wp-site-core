<?php
/**
 * Title: Button Group
 * Description: Box displaying a group of buttons.
 * Categories: components, status
 * Keywords: button, group, component, site, action
 * Slug: wp-site-core/component/button-group
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args     = $args ?? [];
$buttons  = $args['buttons']  ?? []; // Tableau d'objets $args pour le composant Button
$attached = $args['attached'] ?? false;
$align    = $args['align']    ?? 'left'; // left, center, right
$vertical = $args['vertical'] ?? false;

// 2. Early return if no buttons are provided
if (empty($buttons)) {
    return;
}

// 3. Classes BEM
$classes = array_filter([
    'btn-group',
    $attached ? 'btn-group--attached' : '',
    $vertical ? 'btn-group--vertical' : '',
    $align !== 'left' ? "btn-group--align-{$align}" : '',
]);
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" role="group">
    <?php foreach ($buttons as $btn_args) : ?>
        <?php wp_site_core_render_pattern('button/button', $btn_args); ?>
    <?php endforeach; ?>
</div>