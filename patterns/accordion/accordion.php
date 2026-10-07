<?php
/**
 * Title: Accordion
 * Description: Accordion component with customizable questions and answers.
 * Categories: content, faq
 * Keywords: accordion, FAQ, questions, answers
 * Slug: wp-site-core/component/accordion
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args           = $args ?? [];
$title          = $args['title'] ?? '';
$items          = $args['items'] ?? [];
$allow_multiple = !empty($args['allow_multiple']);
$open_first     = $args['open_first'] ?? true;
$heading_level  = $args['heading_level'] ?? 3;

// 2. Validate and sanitize input
if (!is_array($items) || empty($items)) {
    return;
}

// 3. Ensure heading level is within valid range (2-6)
$heading_level = filter_var($heading_level, FILTER_VALIDATE_INT);
if ($heading_level === false || $heading_level < 2 || $heading_level > 6) {
    $heading_level = 3;
}

// 4. Generate unique IDs for the accordion and track initial open state
$accordion_id = wp_unique_id('accordion-');
$initial_open_used = false;
?>
<section class="accordion" aria-label="<?php echo esc_attr($title ?: __('Questions fréquentes', 'wp-theme-test')); ?>">
    <div class="container accordion__wrapper">
        <?php if ($title !== '') : ?>
            <h2 class="accordion__title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <div
            id="<?php echo esc_attr($accordion_id); ?>"
            class="accordion__items"
            data-accordion
            data-accordion-mode="<?php echo $allow_multiple ? 'multiple' : 'single'; ?>"
        >
            <?php foreach ($items as $index => $item) :
                if (!is_array($item)) {
                    continue;
                }

                $question = $item['question'] ?? '';
                $answer = $item['answer'] ?? '';
                if (!is_scalar($question) || (string) $question === '' || !is_scalar($answer)) {
                    continue;
                }

                $is_open = !empty($item['open']);
                if ($open_first && !$initial_open_used) {
                    $is_open = true;
                }
                if (!$allow_multiple && $initial_open_used) {
                    $is_open = false;
                }
                if ($is_open) {
                    $initial_open_used = true;
                }

                $item_id = $accordion_id . '-' . (int) $index;
                $trigger_id = $item_id . '-trigger';
                $panel_id = $item_id . '-panel';
                ?>
                <div class="accordion__item<?php echo $is_open ? ' accordion__item--open' : ''; ?>">
                    <h<?php echo (int) $heading_level; ?> class="accordion__heading">
                        <button
                            type="button"
                            id="<?php echo esc_attr($trigger_id); ?>"
                            class="accordion__trigger"
                            aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                        >
                            <span class="accordion__question"><?php echo esc_html((string) $question); ?></span>
                            <span class="accordion__icon" aria-hidden="true"></span>
                        </button>
                    </h<?php echo (int) $heading_level; ?>>
                    <div
                        id="<?php echo esc_attr($panel_id); ?>"
                        class="accordion__panel"
                        role="region"
                        aria-labelledby="<?php echo esc_attr($trigger_id); ?>"
                        <?php echo $is_open ? '' : 'hidden'; ?>
                    >
                        <div class="accordion__content">
                            <?php echo wp_kses_post((string) $answer); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
