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
$args       = $args ?? [];
$title      = $args['title']       ?? '';
$url        = $args['url']         ?? '';
$text       = $args['text']        ?? '';
$meta       = $args['meta']        ?? '';
$image_src  = $args['image_src']   ?? '';
$image_alt  = $args['image_alt']   ?? $title;
$badge      = $args['badge']       ?? [];     // Array $args pour composant Badge
$horizontal = $args['horizontal']  ?? false;
$clickable  = $args['clickable']   ?? !empty($url);
$footer     = $args['footer']      ?? null;

// 2. Classes BEM
$classes = array_filter([
    'card',
    $horizontal ? 'card--horizontal' : '',
    $clickable ? 'card--clickable' : '',
]);
?>
<article class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if (!empty($image_src)) : ?>
        <div class="card__media">
            <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
            <?php if (!empty($badge)) : ?>
                <div class="card__badge">
                    <?php wp_site_core_render_pattern('badge/badge', $badge); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card__body">
        <?php if ($meta || $title) : ?>
            <header class="card__header">
                <?php if ($meta) : ?>
                    <span class="card__meta"><?php echo esc_html($meta); ?></span>
                <?php endif; ?>

                <?php if ($title) : ?>
                    <h3 class="card__title">
                        <?php if ($url) : ?>
                            <a href="<?php echo esc_url($url); ?>" class="card__title-link">
                                <?php echo esc_html($title); ?>
                            </a>
                        <?php else : ?>
                            <?php echo esc_html($title); ?>
                        <?php endif; ?>
                    </h3>
                <?php endif; ?>
            </header>
        <?php endif; ?>

        <?php if ($text) : ?>
            <p class="card__text"><?php echo esc_html($text); ?></p>
        <?php endif; ?>

        <?php if ($footer) : ?>
            <footer class="card__footer">
                <?php echo $footer; ?>
            </footer>
        <?php endif; ?>
    </div>
</article>