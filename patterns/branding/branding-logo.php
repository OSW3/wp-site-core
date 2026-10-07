<?php
/**
 * Title: Brand Logo
 * Description: Box displaying the brand logo of the site.
 * Categories: components, status
 * Keywords: brand, logo, site, label, component
 * Slug: wp-site-core/component/branding-logo
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];
$src  = $args['src'] ?? '/assets/images/logo.png';
?>
<a href="<?= esc_url(home_url('/')); ?>" rel="home">
    <img src="<?= esc_url( get_template_directory_uri() . $src ); ?>" alt="<?= esc_attr( get_bloginfo( 'name' ) ); ?>">
</a>