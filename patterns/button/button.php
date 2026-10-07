<?php
/**
 * Title: Button
 * Description: Box displaying a button component.
 * Categories: components, status
 * Keywords: button, component, site, action
 * Slug: wp-site-core/component/button
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
$type       = $args['type']       ?? 'primary'; // primary, secondary, outline, ghost, danger
$size       = $args['size']       ?? 'md';      // sm, md, lg
$is_full    = $args['full']       ?? false;
$is_disabled= $args['disabled']   ?? false;
$target     = $args['target']     ?? '_self';
$icon_left  = $args['icon_left']  ?? null;      // SVG brut
$icon_right = $args['icon_right'] ?? null;      // SVG brut
$btn_type   = $args['btn_type']   ?? 'button';  // button, submit, reset

// 2. Construction of BEM classes
$classes = array_filter([
    'btn',
    "btn--{$type}",
    $size !== 'md' ? "btn--{$size}" : '',
    $is_full ? 'btn--full' : '',
    $is_disabled ? 'btn--disabled' : '',
]);

// 3. Determine the HTML tag to use based on the presence of a URL
$tag = !empty($url) ? 'a' : 'button';
?>
<?php if ($tag === 'a') : ?>
    <a 
        href="<?php echo esc_url($url); ?>" 
        class="<?php echo esc_attr(implode(' ', $classes)); ?>"
        target="<?php echo esc_attr($target); ?>"
        <?php echo $target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
        <?php echo $is_disabled ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
    >
        <?php if ($icon_left) : ?>
            <span class="btn__icon btn__icon--left" aria-hidden="true"><?php echo $icon_left; ?></span>
        <?php endif; ?>

        <span class="btn__label"><?php echo esc_html($label); ?></span>

        <?php if ($icon_right) : ?>
            <span class="btn__icon btn__icon--right" aria-hidden="true"><?php echo $icon_right; ?></span>
        <?php endif; ?>
    </a>
<?php else : ?>
    <button 
        type="<?php echo esc_attr($btn_type); ?>" 
        class="<?php echo esc_attr(implode(' ', $classes)); ?>"
        <?php echo $is_disabled ? 'disabled' : ''; ?>
    >
        <?php if ($icon_left) : ?>
            <span class="btn__icon btn__icon--left" aria-hidden="true"><?php echo $icon_left; ?></span>
        <?php endif; ?>

        <span class="btn__label"><?php echo esc_html($label); ?></span>

        <?php if ($icon_right) : ?>
            <span class="btn__icon btn__icon--right" aria-hidden="true"><?php echo $icon_right; ?></span>
        <?php endif; ?>
    </button>
<?php endif; ?>