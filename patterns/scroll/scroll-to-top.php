<?php
/**
 * Title: Scroll To Top
 * Description: Scroll to top component for quickly navigating back to the top of the page.
 * Categories: components, status
 * Keywords: scroll, top, navigation, back-to-top
 * Slug: wp-site-core/component/scroll-to-top
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];
$label = $args['label'] ?? __('Retour en haut', 'wp-site-core');
$type = $args['type'] ?? 'primary';
$position = $args['position'] ?? 'right';
$threshold = $args['threshold'] ?? 300;
$show_label = !empty($args['show_label']);
$smooth = !isset($args['smooth']) || !empty($args['smooth']);

// 2. Validation of input parameters
if (!is_string($label) || trim($label) === '') {
    _doing_it_wrong('component/scroll-to-top', 'label must be a non-empty string.', '1.0.0');
    $label = __('Retour en haut', 'wp-site-core');
}
if (!in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'], true)) {
    _doing_it_wrong('component/scroll-to-top', 'Invalid button type.', '1.0.0');
    $type = 'primary';
}
if (!in_array($position, ['left', 'right'], true)) {
    _doing_it_wrong('component/scroll-to-top', 'position must be left or right.', '1.0.0');
    $position = 'right';
}
if (!is_numeric($threshold) || !is_finite((float) $threshold) || (float) $threshold < 0) {
    _doing_it_wrong('component/scroll-to-top', 'threshold must be a finite, non-negative number.', '1.0.0');
    $threshold = 300;
}
?>
<a href="#" class="scroll-to-top scroll-to-top--<?php echo esc_attr($position); ?> btn btn--<?php echo esc_attr($type); ?>"
    data-scroll-to-top data-threshold="<?php echo esc_attr($threshold); ?>" data-smooth="<?php echo $smooth ? 'true' : 'false'; ?>">
    <svg class="scroll-to-top__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
        <path d="M12 19V5M5 12l7-7 7 7" />
    </svg>
    <span class="<?php echo $show_label ? 'scroll-to-top__label' : 'scroll-to-top__label--hidden'; ?>"><?php echo esc_html($label); ?></span>
</a>
