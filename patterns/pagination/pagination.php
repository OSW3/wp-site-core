<?php
/**
 * Title: Pagination
 * Description: Pagination component for navigating through pages.
 * Categories: components, status
 * Keywords: pagination, navigation, pages, next, previous
 * Slug: wp-site-core/component/pagination
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
global $wp_query;

$args     = $args ?? [];
$current  = $args['current'] ?? max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$total    = $args['total'] ?? max(1, (int) ($wp_query->max_num_pages ?? 1));
$mid_size = $args['mid_size'] ?? 2;
$end_size = $args['end_size'] ?? 1;
$align    = $args['align'] ?? 'center';
$size     = $args['size'] ?? 'medium';
$label    = $args['label'] ?? __('Pagination', 'wp-theme-test');

foreach (['current' => 1, 'total' => 0, 'mid_size' => 0, 'end_size' => 1] as $key => $minimum) {
    $value = filter_var($$key, FILTER_VALIDATE_INT);
    if ($value === false || $value < $minimum) {
        _doing_it_wrong('component/pagination', $key . ' must be an integer within its allowed range.', '1.0.0');
        return;
    }
    $$key = $value;
}
if ($total === 0) {
    return;
}
if ($current > $total) {
    _doing_it_wrong('component/pagination', 'current cannot exceed total.', '1.0.0');
    $current = $total;
}
if (!in_array($align, ['left', 'center', 'right'], true) || !in_array($size, ['small', 'medium', 'large'], true)) {
    _doing_it_wrong('component/pagination', 'Invalid alignment or size.', '1.0.0');
    return;
}
if (!is_string($label) || trim($label) === '' || (isset($args['base']) && !is_string($args['base']))) {
    _doing_it_wrong('component/pagination', 'label and base must be strings.', '1.0.0');
    return;
}
$links = paginate_links([
    'base' => $args['base'] ?? str_replace('999999999', '%#%', get_pagenum_link(999999999)),
    'format' => '?paged=%#%',
    'current' => $current,
    'total' => $total,
    'mid_size' => $mid_size,
    'end_size' => $end_size,
    'prev_next' => $args['prev_next'] ?? true,
    'prev_text' => esc_html__('Précédent', 'wp-theme-test'),
    'next_text' => esc_html__('Suivant', 'wp-theme-test'),
    'before_page_number' => '<span class="pagination__sr">' . esc_html__('Page ', 'wp-theme-test') . '</span>',
    'type' => 'array',
    'aria_current' => 'page',
]);
if (!$links) {
    return;
}
?>
<nav class="pagination pagination--<?php echo esc_attr($align); ?> pagination--<?php echo esc_attr($size); ?>" aria-label="<?php echo esc_attr($label); ?>">
    <ul class="pagination__list">
        <?php foreach ($links as $link) : ?>
            <li><?php echo wp_kses_post($link); ?></li>
        <?php endforeach; ?>
    </ul>
</nav>
