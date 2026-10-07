<?php
/**
 * Title: Alert Warning
 * Description: Alert component for displaying warning messages.
 * Categories: components, status
 * Keywords: alert, warning, message
 * Slug: wp-site-core/component/alert-warning
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$type    = $args['type'] ?? 'default';
$title   = $args['title'] ?? 'Attention :';
$message = $args['message'] ?? 'Message par défaut...';

// 2. Prepare arguments for the alert component
$args = [
    'title'   => $title,
    'message' => $message,
    'type'    => 'warning',
];

// 3. Include the main alert component with the prepared arguments
include __DIR__ . '/alert.php';