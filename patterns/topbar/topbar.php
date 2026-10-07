<?php
/**
 * Title: Topbar
 * Description: Topbar component for displaying informational messages.
 * Categories: components, status
 * Keywords: topbar, info, information, message
 * Slug: wp-site-core/component/topbar
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$content = $args['content'] ?? '';
$links   = $args['links'] ?? [];
$type    = $args['type'] ?? 'secondary';
$sticky  = !empty($args['sticky']);
$label   = $args['label'] ?? __('Informations utiles', 'wp-theme-test');

// 2. Validation of input parameters
if (!in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'], true)) {
    _doing_it_wrong('component/topbar', 'Invalid type.', '1.0.0');
    $type = 'secondary';
}
if (!is_string($content) || !is_array($links) || !is_string($label) || trim($label) === '') {
    _doing_it_wrong('component/topbar', 'content and label must be strings and links must be an array.', '1.0.0');
    return;
}
?>
<aside class="topbar topbar--<?php echo esc_attr($type); ?><?php echo $sticky ? ' topbar--sticky' : ''; ?>" aria-label="<?php echo esc_attr($label); ?>" data-topbar>
    <div class="container topbar__inner">
        <?php if ($content !== '') : ?><div class="topbar__content"><?php echo wp_kses_post($content); ?></div><?php endif; ?>
        <?php if ($links) : ?>
            <ul class="topbar__links">
                <?php foreach ($links as $link) : ?>
                    <?php
                    if (!is_array($link) || !isset($link['label'], $link['url']) || !is_string($link['label']) || !is_string($link['url'])) {
                        _doing_it_wrong('component/topbar', 'Each link requires a string label and URL.', '1.0.0');
                        continue;
                    }
                    ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</aside>