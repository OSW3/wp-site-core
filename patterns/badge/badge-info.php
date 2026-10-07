<?php
/**
 * Title: Badge Info
 * Description: Info variant of the badge component.
 * Categories: components, status
 * Keywords: badge, info, status, label, component
 * Slug: wp-site-core/component/badge-info
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args  = $args ?? [];
$label = $args['label'] ?? 'Information';
$size  = $args['size']  ?? 'md';
$icon  = $args['icon']  ?? null;

// 2. Prepare arguments for the base badge component with the info type
$args = [
    'label' => $label,
    'type'  => 'info',
    'size'  => $size,
    'icon'  => $icon,
];

// 3. Resolve and include the base badge component file directly
include __DIR__ . '/badge.php';