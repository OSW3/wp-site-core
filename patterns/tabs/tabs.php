<?php
/**
 * Title: Tabs
 * Description: Tabs component for displaying tabbed content.
 * Categories: components, status
 * Keywords: tabs, tabbed content, navigation
 * Slug: wp-site-core/component/tabs
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];
$items = $args['items'] ?? [];
$label = $args['label'] ?? __('Onglets', 'wp-site-core');
$orientation = $args['orientation'] ?? 'horizontal';
$activation = $args['activation'] ?? 'automatic';
$active = $args['active'] ?? 0;

// 2. Validation of input parameters
if (!is_array($items) || !$items || !is_string($label) || trim($label) === ''
    || !in_array($orientation, ['horizontal', 'vertical'], true) || !in_array($activation, ['automatic', 'manual'], true)) {
    _doing_it_wrong('component/tabs', 'Provide items, a label, a valid orientation and activation mode.', '1.0.0');
    return;
}
$items = array_values($items);
foreach ($items as $item) {
    if (!is_array($item) || !isset($item['label'], $item['content']) || !is_string($item['label']) || trim($item['label']) === '' || !is_string($item['content'])) {
        _doing_it_wrong('component/tabs', 'Each item requires a non-empty label and HTML content.', '1.0.0');
        return;
    }
}
$enabled = array_keys(array_filter($items, static fn($item) => empty($item['disabled'])));
if (!$enabled) {
    _doing_it_wrong('component/tabs', 'At least one tab must be enabled.', '1.0.0');
    return;
}
$active = filter_var($active, FILTER_VALIDATE_INT);
if ($active === false || !in_array($active, $enabled, true)) {
    _doing_it_wrong('component/tabs', 'active must refer to an enabled tab index.', '1.0.0');
    $active = $enabled[0];
}

// 3. Generate a unique ID for the tabs component
$id = wp_unique_id('tabs-');
?>
<div class="tabs tabs--<?php echo esc_attr($orientation); ?>" data-tabs data-active="<?php echo esc_attr($active); ?>" data-activation="<?php echo esc_attr($activation); ?>" data-orientation="<?php echo esc_attr($orientation); ?>">
    <nav class="tabs__list" data-tab-list aria-label="<?php echo esc_attr($label); ?>">
        <?php foreach ($items as $index => $item) : ?>
            <?php if (!empty($item['disabled'])) : ?>
                <span id="<?php echo esc_attr($id . '-tab-' . $index); ?>" class="tabs__tab" data-tab data-tab-disabled aria-disabled="true"><?php echo esc_html($item['label']); ?></span>
            <?php else : ?>
                <a id="<?php echo esc_attr($id . '-tab-' . $index); ?>" href="#<?php echo esc_attr($id . '-panel-' . $index); ?>" class="tabs__tab" data-tab><?php echo esc_html($item['label']); ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
    <div class="tabs__panels">
        <?php foreach ($items as $index => $item) : ?>
            <section id="<?php echo esc_attr($id . '-panel-' . $index); ?>" class="tabs__panel" data-tab-panel aria-labelledby="<?php echo esc_attr($id . '-tab-' . $index); ?>">
                <?php echo wp_kses_post($item['content']); ?>
            </section>
        <?php endforeach; ?>
    </div>
</div>