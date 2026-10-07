<?php
/**
 * Title: Comment Component
 * Description: Comment component for displaying user comments.
 * Categories: components, status
 * Keywords: comment, user, feedback, message
 * Slug: wp-site-core/component/comment
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];
$title = $args['title'] ?? __('Commentaires', 'wp-theme-test');

// 2. Validate title argument
if (!is_string($title) || trim($title) === '') {
    _doing_it_wrong('component/comment', 'title must be a non-empty string.', '1.0.0');
    return;
}

// 3. Return early if not a singular post or if the post is password protected
if (!is_singular() || !get_post() || post_password_required()) {
    return;
}

// 4. Generate a unique ID for the comments heading
$heading_id = wp_unique_id('comments-title-');
?>
<section class="comments" aria-labelledby="<?php echo esc_attr($heading_id); ?>">
    <h2 id="<?php echo esc_attr($heading_id); ?>" class="comments__title"><?php echo esc_html($title); ?></h2>
    <?php comments_template('/patterns/comment/thread.php'); ?>
</section>
