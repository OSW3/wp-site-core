<?php
/**
 * Title: Dropdown
 * Description: Dropdown component for displaying a list of navigational links.
 * Categories: components, status
 * Keywords: dropdown, navigation, menu, links
 * Slug: wp-site-core/component/dropdown
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args       = $args ?? [];
$label      = $args['label'] ?? 'Navigation';
$items      = $args['items'] ?? [];
$type       = $args['type'] ?? 'secondary';
$direction  = $args['direction'] ?? 'dropdown';
$types      = ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'ghost', 'outline'];
$directions = ['dropdown', 'dropup', 'dropleft', 'dropright'];

// 2. Validate type and direction
if (!in_array($type, $types, true)) {
    _doing_it_wrong('component/dropdown', 'Invalid button type.', '1.0.0');
    $type = 'secondary';
}
if (!in_array($direction, $directions, true)) {
    _doing_it_wrong('component/dropdown', 'Invalid dropdown direction.', '1.0.0');
    $direction = 'dropdown';
}

// 3. Generate a unique ID for the dropdown
$id = wp_unique_id('dropdown-');

// 4. Validate items array
if (!is_array($items)) {
    _doing_it_wrong('component/dropdown', 'items must be an array.', '1.0.0');
    $items = [];
}
?>
<span class="dropdown dropdown--<?php echo esc_attr($direction); ?>" data-floating="dropdown" data-direction="<?php echo esc_attr($direction); ?>">
    <button type="button" class="btn btn--<?php echo esc_attr($type); ?>" data-floating-trigger aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>">
        <?php echo esc_html($label); ?>
        <span class="dropdown__caret" aria-hidden="true">&#9662;</span>
    </button>
    <div id="<?php echo esc_attr($id); ?>" class="dropdown__panel" popover="manual" data-floating-panel>
        <ul class="dropdown__list">
            <?php foreach ($items as $item) : ?>
                <?php
                if (!is_array($item) || !isset($item['label'], $item['url'])) {
                    _doing_it_wrong('component/dropdown', 'Each item needs a label and URL.', '1.0.0');
                    continue;
                }
                ?>
                <li><a class="dropdown__link" href="<?php echo esc_url($item['url']); ?>"><?php echo esc_html($item['label']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</span>
