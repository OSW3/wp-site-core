<?php
/**
 * Title: Author Box
 * Description: Box displaying author information.
 * Categories: components, status
 * Keywords: author, box, profile, information
 * Slug: wp-site-core/component/author-box
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args   = $args ?? [];
$name   = $args['name']   ?? '';
$role   = $args['role']   ?? '';
$bio    = $args['bio']    ?? '';
$avatar = $args['avatar'] ?? []; // Tableau $args pour le composant Avatar
$links  = $args['links']  ?? []; // Array de ['url' => '...', 'label' => '...', 'icon' => '...']
$variant= $args['variant']?? 'default'; // default, card

// 2. Generate CSS classes based on BEM methodology
$classes = array_filter([
    'author-box',
    $variant !== 'default' ? "author-box--{$variant}" : '',
]);
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if (!empty($avatar)) : ?>
        <div class="author-box__avatar">
            <?php
            // Réutilisation du pattern Avatar avec ses arguments
            wp_site_core_render_pattern('avatar/avatar', array_merge([
                'size' => 'lg',
            ], $avatar));
            ?>
        </div>
    <?php endif; ?>

    <div class="author-box__content">
        <div class="author-box__header">
            <?php if ($name) : ?>
                <h3 class="author-box__name"><?php echo esc_html($name); ?></h3>
            <?php endif; ?>

            <?php if ($role) : ?>
                <span class="author-box__role"><?php echo esc_html($role); ?></span>
            <?php endif; ?>
        </div>

        <?php if ($bio) : ?>
            <p class="author-box__bio"><?php echo esc_html($bio); ?></p>
        <?php endif; ?>

        <?php if (!empty($links)) : ?>
            <ul class="author-box__links">
                <?php foreach ($links as $link) : ?>
                    <li>
                        <a href="<?php echo esc_url($link['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($link['label'] ?? ''); ?>">
                            <?php echo $link['icon'] ?? esc_html($link['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>