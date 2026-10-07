<?php
/**
 * Title: Button Outline
 * Description: Box displaying an outline button component.
 * Categories: components, status
 * Keywords: button, outline, component, site, action
 * Slug: wp-site-core/component/button-outline
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args       = $args ?? [];
$label      = $args['label']      ?? 'Bouton';
$url        = $args['url']        ?? '';
$size       = $args['size']       ?? 'md';      // sm, md, lg
$is_full    = $args['full']       ?? false;
$is_disabled= $args['disabled']   ?? false;
$target     = $args['target']     ?? '_self';
$icon_left  = $args['icon_left']  ?? null;      // SVG brut
$icon_right = $args['icon_right'] ?? null;      // SVG brut
$btn_type   = $args['btn_type']   ?? 'button';  // button, submit, reset


// 2. Direct inclusion by specifically overriding the type
$args = [
    'label'       => $label,
    'type'        => 'outline',
    'size'        => $size,
    'icon_left'   => $icon_left,
    'icon_right'  => $icon_right,
    'is_full'     => $is_full,
    'is_disabled' => $is_disabled,
    'target'      => $target,
    'btn_type'    => $btn_type,
];

// 3. Resolve and directly include the base button file
include __DIR__ . '/button.php';