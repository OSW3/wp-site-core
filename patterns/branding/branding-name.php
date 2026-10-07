<?php
/**
 * Title: Brand Name
 * Description: Box displaying the brand name of the site.
 * Categories: components, status
 * Keywords: brand, name, site, label, component
 * Slug: wp-site-core/component/branding-name
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */
?>
<a href="<?= esc_url(home_url('/')); ?>" rel="home"><?= get_bloginfo('name'); ?></a>