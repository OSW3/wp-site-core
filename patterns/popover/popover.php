<?php
/**
 * Title: Popover
 * Description: Popover component for displaying additional information on demand.
 * Categories: components, status
 * Keywords: popover, info, information, message
 * Slug: wp-site-core/component/popover
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args    = $args ?? [];
$label   = $args['label'] ?? 'En savoir plus';
$title   = $args['title'] ?? '';
$content = $args['content'] ?? '';
$element = $args['element'] ?? 'button';

// 2. Validation of input parameters
if (!in_array($element, ['button', 'a', 'span'], true)) {
    _doing_it_wrong('component/popover', 'element must be button, a or span.', '1.0.0');
    $element = 'button';
}

// 3. Additional setup for inline popovers and unique ID generation
$inline    = !empty($args['inline']);
$url       = $args['url'] ?? '';
$panel_tag = $inline ? 'span' : 'div';

// 4. Generate a unique ID for the popover panel
$id = wp_unique_id('popover-');
?>
<<?php echo $panel_tag; ?> class="popover<?php echo $inline ? ' popover--inline' : ''; ?>" data-floating="popover">
    <<?php echo $element; ?>
        <?php if ($element === 'button') : ?>type="button"<?php endif; ?>
        <?php if ($element === 'a') : ?>href="<?php echo esc_url($url ?: '#' . $id); ?>"<?php endif; ?>
        <?php if ($element === 'a') : ?>role="button"<?php endif; ?>
        <?php if ($element === 'span') : ?>role="button" tabindex="0"<?php endif; ?>
        class="<?php echo $inline ? 'popover__trigger' : 'btn btn--outline'; ?>"
        data-floating-trigger aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>"
    >
        <?php echo esc_html($label); ?>
    </<?php echo $element; ?>>
    <<?php echo $panel_tag; ?> id="<?php echo esc_attr($id); ?>" class="popover__panel" popover="manual" data-floating-panel>
        <?php if ($title !== '') : ?>
            <?php if ($inline) : ?>
                <strong class="popover__title"><?php echo esc_html($title); ?></strong>
            <?php else : ?>
                <h3 class="popover__title"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>
        <?php endif; ?>
        <<?php echo $panel_tag; ?> class="popover__content"><?php
            echo $inline ? wp_kses($content, [
                'a' => ['href' => true, 'title' => true],
                'strong' => [], 'em' => [], 'b' => [], 'i' => [], 'br' => [],
                'code' => [], 'span' => [], 'small' => [], 'sub' => [], 'sup' => [],
            ]) : wp_kses_post($content);
        ?></<?php echo $panel_tag; ?>>
        <button type="button" class="btn btn--ghost btn--sm" data-floating-close><?php esc_html_e('Fermer', 'wp-theme-test'); ?></button>
    </<?php echo $panel_tag; ?>>
</<?php echo $panel_tag; ?>>
