<?php
/**
 * Title: Badge
 * Description: Badge component for displaying status or labels.
 * Categories: components, status
 * Keywords: badge, status, label, component
 * Slug: wp-site-core/component/badge
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args  = $args ?? [];
$label = $args['label'] ?? 'Nouveau';
$type  = $args['type'] ?? 'default';
$size  = $args['size'] ?? 'md';
$icon  = $args['icon'] ?? null;

// 2. Construct BEM classes for the badge component
$badge_classes = array_filter([
    'badge',
    "badge--{$type}",
    $size !== 'md' ? "badge--{$size}" : '',
]);
?>
<span class="<?php echo esc_attr(implode(' ', $badge_classes)); ?>">
    <?php if ($icon) : ?>
        <span class="badge__icon" aria-hidden="true">
            <?php echo $icon; ?>
        </span>
    <?php endif; ?>
    <span class="badge__label"><?php echo esc_html($label); ?></span>
</span>