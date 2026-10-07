<?php
/**
 * Title: Breadcrumb
 * Description: Box displaying the breadcrumb navigation of the site.
 * Categories: components, status
 * Keywords: breadcrumb, navigation, site, component
 * Slug: wp-site-core/component/breadcrumb
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args  = $args ?? [];
$items = $args['items'] ?? [
    ['label' => 'Accueil', 'url' => home_url('/')]
];

// 2. Return early if no items are provided
if (empty($items)) {
    return;
}
?>
<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($items as $index => $item) : 
            $is_last = ($index === count($items) - 1) || empty($item['url']);
            $position = $index + 1;
        ?>
            <li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if (!$is_last) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>" class="breadcrumb__link" itemprop="item">
                        <span itemprop="name"><?php echo esc_html($item['label']); ?></span>
                    </a>
                <?php else : ?>
                    <span class="breadcrumb__current" aria-current="page" itemprop="name">
                        <?php echo esc_html($item['label']); ?>
                    </span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo (int) $position; ?>" />
            </li>
        <?php endforeach; ?>
    </ol>
</nav>