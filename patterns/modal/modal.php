<?php
/**
 * Title: Modal
 * Description: Modal component for displaying dialog windows.
 * Categories: components, status
 * Keywords: modal, dialog, window, popup
 * Slug: wp-site-core/component/modal
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args        = $args ?? [];
$modal_id    = $args['modal_id'] ?? wp_unique_id('modal-');
$title       = $args['title'] ?? 'Fenêtre de dialogue';
$content     = $args['content'] ?? '';
$size        = $args['size'] ?? 'medium';
$close_label = $args['close_label'] ?? 'Fermer';

// 2. Validation of input parameters
$allowed_sizes = ['small', 'medium', 'large'];

if (!in_array($size, $allowed_sizes, true)) {
    $size = 'medium';
}

// 3. Sanitize and prepare IDs for the modal and its title
$modal_id = sanitize_html_class($modal_id);
$title_id = $modal_id . '-title';
?>
<dialog
    id="<?php echo esc_attr($modal_id); ?>"
    class="modal<?php echo $size !== 'medium' ? ' modal--' . esc_attr($size) : ''; ?>"
    aria-labelledby="<?php echo esc_attr($title_id); ?>"
    data-modal
>
    <div class="modal__header">
        <h2 id="<?php echo esc_attr($title_id); ?>" class="modal__title">
            <?php echo esc_html($title); ?>
        </h2>
        <button
            type="button"
            class="modal__close"
            aria-label="<?php echo esc_attr($close_label); ?>"
            data-modal-close
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal__content">
        <?php echo wp_kses_post($content); ?>
    </div>
</dialog>
