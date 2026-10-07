<?php
/**
 * Title: Brand Custom Logo
 * Slug: brand-custom-logo
 * Block Types: wp-site-core/component
 * Categories: branding
 * Description: Displays the brand logo of the site.
 */
?>
<?php if (has_custom_logo()) : ?>
    <div class="custom-logo-link">
        <?php the_custom_logo(); ?>
    </div>
<?php endif; ?>