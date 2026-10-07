<?php
/**
 * Title: Author Box WP
 * Description: Box displaying WordPress author information.
 * Categories: components
 * Keywords: author, WordPress, user, profile
 * Slug: component/author-box-wp
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args      = $args ?? [];
$user_id   = $args['user_id'] ?? get_the_author_meta('ID');
$name      = get_the_author_meta('display_name', $user_id);
$bio       = get_the_author_meta('description', $user_id);
$avatar_url= get_avatar_url($user_id, ['size' => 128]);

// 2. Include the main author box component with the prepared arguments
include __DIR__ . '/author-box.php';