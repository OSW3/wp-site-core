<?php
/**
 * Title: Alert Info
 * Description: Alert component for displaying informational messages.
 * Categories: components, status
 * Keywords: alert, info, information, message
 * Slug: wp-site-core/component/alert-info
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
$title   = $args['title'] ?? 'Information :';
$message = $args['message'] ?? 'Message par défaut...';

// 2. Prepare arguments for the alert component
$args = [
    'title'   => $title,
    'message' => $message,
    'type'    => 'info',
];

// 3. Include the main alert component with the prepared arguments
include __DIR__ . '/alert.php';