<?php
/**
 * Title: Progression
 * Description: Progress component for displaying progression status.
 * Categories: components, status
 * Keywords: progress, progression, status, bar
 * Slug: wp-site-core/component/progress
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args          = $args ?? [];
$label         = $args['label'] ?? __('Progression', 'wp-theme-test');
$max           = $args['max'] ?? 100;
$value         = array_key_exists('value', $args) ? $args['value'] : 0;
$indeterminate = $value === null;
$type          = $args['type'] ?? 'primary';
$striped       = !empty($args['striped']);
$animated      = !empty($args['animated']);
$show_value    = $args['show_value'] ?? true;

// 2. Validation of input parameters
if (!is_string($label) || trim($label) === '' || !is_numeric($max) || !is_finite((float) $max) || (float) $max <= 0
    || (!$indeterminate && (!is_numeric($value) || !is_finite((float) $value)))) {
    _doing_it_wrong('component/progress', 'Provide a non-empty label, a positive finite max and a finite value or null.', '1.0.0');
    return;
}
$max = (float) $max;
if (!$indeterminate) {
    $value = (float) $value;
    if ($value < 0 || $value > $max) {
        _doing_it_wrong('component/progress', 'value must be between 0 and max.', '1.0.0');
        $value = max(0, min($max, $value));
    }
}
if (!in_array($type, ['primary', 'secondary', 'info', 'success', 'danger', 'warning'], true)) {
    _doing_it_wrong('component/progress', 'Invalid type.', '1.0.0');
    $type = 'primary';
}

// 3. Generate a unique ID for the progress component
$id = $args['id'] ?? wp_unique_id('progress-');
if (!is_string($id) || !preg_match('/^[A-Za-z][A-Za-z0-9_-]*$/', $id)) {
    _doing_it_wrong('component/progress', 'id must be a valid HTML identifier.', '1.0.0');
    return;
}
$percentage = $indeterminate ? 0 : round($value / $max * 100, 2);
?>
<div id="<?php echo esc_attr($id); ?>" class="progress progress--<?php echo esc_attr($type); ?><?php echo $striped ? ' progress--striped' : ''; ?><?php echo $animated ? ' progress--animated' : ''; ?><?php echo $indeterminate ? ' progress--indeterminate' : ''; ?>" data-progress data-max="<?php echo esc_attr($max); ?>">
    <div class="progress__heading">
        <span id="<?php echo esc_attr($id); ?>-label"><?php echo esc_html($label); ?></span>
        <?php if ($show_value) : ?><span data-progress-text aria-hidden="true"><?php echo $indeterminate ? esc_html__('En cours…', 'wp-theme-test') : esc_html($percentage . '%'); ?></span><?php endif; ?>
    </div>
    <div class="progress__track" role="progressbar" aria-labelledby="<?php echo esc_attr($id); ?>-label" aria-valuemin="0" aria-valuemax="<?php echo esc_attr($max); ?>"<?php if (!$indeterminate) : ?> aria-valuenow="<?php echo esc_attr($value); ?>"<?php endif; ?>>
        <span class="progress__bar" data-progress-bar style="width: <?php echo esc_attr($percentage); ?>%" aria-hidden="true"></span>
    </div>
    <span data-progress-pending hidden><?php esc_html_e('En cours…', 'wp-theme-test'); ?></span>
</div>
