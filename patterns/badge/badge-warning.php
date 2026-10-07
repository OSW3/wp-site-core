<?php
/**
 * Title: Badge Warning
 * Description: Warning variant of the badge component.
 * Categories: components, status
 * Keywords: badge, warning, status, label, component
 * Slug: wp-site-core/component/badge-warning
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args  = $args ?? [];
$label = $args['label'] ?? 'Avertissement';
$size  = $args['size']  ?? 'md';
$icon  = $args['icon']  ?? null;

// 2. Prepare arguments for the base badge component with the warning type
$args = [
    'label' => $label,
    'type'  => 'warning',
    'size'  => $size,
    'icon'  => $icon,
];

// 3. Resolve and include the base badge component file directly
include __DIR__ . '/badge.php';