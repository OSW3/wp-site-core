<?php
/**
 * Title: Dropdown Primary
 * Description: Dropdown component for displaying a list of navigational links.
 * Categories: components, status
 * Keywords: dropdown, navigation, menu, links
 * Slug: wp-site-core/component/dropdown-primary
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args       = $args ?? [];
$label      = $args['label'] ?? 'Navigation';
$items      = $args['items'] ?? [];
$direction  = $args['direction'] ?? 'dropdown';
$types      = ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'];

// 2. Prepare arguments for the primary dropdown component
$args = [
    'label' => $label,
    'items' => $items,
    'direction' => $direction,
    'type' => 'primary',
];

// 3. Include the primary dropdown component
include __DIR__ . '/dropdown.php';