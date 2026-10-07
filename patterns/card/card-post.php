<?php
/**
 * Title: Card Post WP
 * Description: Card component for displaying WordPress post information.
 * Categories: components, status
 * Keywords: card, post, wordpress, information
 * Slug: wp-site-core/component/card-post
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$post_id = $args['post_id'] ?? get_the_ID();

// 2. Return early if no post ID is available
if (!$post_id) {
    return;
}

// 3. Get post categories and select the first one as the primary category
$categories = get_the_category($post_id);
$category   = !empty($categories) ? $categories[0]->name : '';

wp_site_core_render_pattern('patterns/card/card', [
    'title'     => get_the_title($post_id),
    'url'       => get_permalink($post_id),
    'text'      => get_the_excerpt($post_id),
    'meta'      => get_the_date('', $post_id),
    'image_src' => get_the_post_thumbnail_url($post_id, 'medium_large'),
    'image_alt' => get_the_title($post_id),
    'badge'     => $category ? ['label' => $category, 'type' => 'primary'] : [],
]);