<?php
/**
 * Title: Avatar
 * Description: Avatar component for displaying user profile pictures.
 * Categories: components, status
 * Keywords: avatar, user, profile, picture
 * Slug: wp-site-core/component/avatar
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args   = $args ?? [];
$src    = $args['src']    ?? '';
$alt    = $args['alt']    ?? '';
$name   = $args['name']   ?? '';
$size   = $args['size']   ?? 'md';      // xs, sm, md, lg, xl
$shape  = $args['shape']  ?? 'circle';  // circle, rounded, square
$status = $args['status'] ?? null;      // online, offline, busy, away, null

// 2. Generate initials if no image is provided
$initials = '';
if (empty($src) && !empty($name)) {
    $words = explode(' ', trim($name));
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1);
    } else {
        $initials = mb_substr($name, 0, 2);
    }
}

// 3. Construct BEM classes
$classes = array_filter([
    'avatar',
    "avatar--{$size}",
    "avatar--{$shape}",
]);
?>
<span class="<?php echo esc_attr(implode(' ', $classes)); ?>" aria-label="<?php echo esc_attr($name ?: $alt ?: 'Avatar'); ?>">
    <?php if (!empty($src)) : ?>
        <img 
            src="<?php echo esc_url($src); ?>" 
            alt="<?php echo esc_attr($alt ?: $name); ?>" 
            class="avatar__img" 
            loading="lazy" 
        />
    <?php elseif (!empty($initials)) : ?>
        <span class="avatar__initials" aria-hidden="true">
            <?php echo esc_html($initials); ?>
        </span>
    <?php else : ?>
        <!-- SVG de secours par défaut -->
        <svg class="avatar__placeholder" viewBox="0 0 24 24" fill="currentColor" width="60%" height="60%">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
    <?php endif; ?>

    <?php if ($status) : ?>
        <span class="avatar__status avatar__status--<?php echo esc_attr($status); ?>" aria-hidden="true"></span>
    <?php endif; ?>
</span>