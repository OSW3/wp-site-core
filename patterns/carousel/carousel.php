<?php
/**
 * Title: Carousel
 * Description: Carousel component for displaying a series of slides.
 * Categories: components, status
 * Keywords: carousel, slides, slider, gallery
 * Slug: wp-site-core/component/carousel
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args          = $args ?? [];
$slides        = $args['slides'] ?? [];
$type          = $args['type'] ?? 'standard';
$transition    = $args['transition'] ?? 'slide';
$per_view      = filter_var($args['per_view'] ?? 1, FILTER_VALIDATE_INT);
$show_controls = $args['show_controls'] ?? true;
$show_dots     = $args['show_dots'] ?? true;
$loop          = $args['loop'] ?? true;
$autoplay      = $args['autoplay'] ?? true;
$show_pause    = $args['show_pause'] ?? true;
$delay         = filter_var($args['delay'] ?? 5000, FILTER_VALIDATE_INT);
$label         = $args['label'] ?? __('Carrousel', 'wp-theme-test');

// 2. Return early if no slides are available
if (!is_array($slides) || empty($slides)) {
    return;
}

// 3. Filter and reindex slides to ensure they are valid arrays or strings
$slides = array_values(array_filter($slides, static function ($slide): bool {
    return is_array($slide) || is_string($slide);
}));

// 4. Return early if no valid slides remain after filtering
if (empty($slides)) {
    return;
}

// 5. Validate and sanitize remaining arguments
$type          = in_array($type, ['standard', 'hero', 'cinematic'], true) ? $type : 'standard';
$transition    = in_array($transition, ['slide', 'fade'], true) ? $transition : 'slide';
$per_view      = $per_view !== false && in_array($per_view, [1, 2, 3], true) ? $per_view : 1;
if ($transition === 'fade') {
    $per_view = 1;
}
$delay         = $delay    !== false ? min(max($delay, 1000), 60000) : 5000;
$show_controls = filter_var($show_controls, FILTER_VALIDATE_BOOLEAN);
$show_dots     = filter_var($show_dots, FILTER_VALIDATE_BOOLEAN);
$loop          = filter_var($loop, FILTER_VALIDATE_BOOLEAN);
$autoplay      = filter_var($autoplay, FILTER_VALIDATE_BOOLEAN);
$show_pause    = filter_var($show_pause, FILTER_VALIDATE_BOOLEAN);
$label         = is_scalar($label) ? (string) $label : __('Carrousel', 'wp-theme-test');
$carousel_id   = wp_unique_id('carousel-');
$has_controls  = $show_controls && count($slides) > 1;
$has_dots      = $show_dots && count($slides) > 1;
$has_autoplay  = $autoplay && count($slides) > 1;

// 6. Prepare CSS classes for the carousel container
$classes = [
    'carousel',
    "carousel--per-view-{$per_view}",
    "carousel--transition-{$transition}",
];
if ($type === 'cinematic') {
    $classes[] = 'carousel--cinematic';
} elseif ($type === 'hero') {
    $classes[] = 'carousel--hero';
}
?>
<section
    id="<?php echo esc_attr($carousel_id); ?>"
    class="<?php echo esc_attr(implode(' ', $classes)); ?>"
    data-component="carousel"
    data-transition="<?php echo esc_attr($transition); ?>"
    data-loop="<?php echo $loop ? 'true' : 'false'; ?>"
    data-autoplay="<?php echo $has_autoplay ? 'true' : 'false'; ?>"
    data-delay="<?php echo (int) $delay; ?>"
    aria-label="<?php echo esc_attr($label); ?>"
>
    <div class="carousel__track-container">
        <ul class="carousel__track">
            <?php foreach ($slides as $index => $slide) :
                if (is_string($slide)) :
                    ?>
                    <li class="carousel__slide">
                        <?php echo wp_kses_post($slide); ?>
                    </li>
                    <?php
                    continue;
                endif;

                $bg_image = $slide['bg_image'] ?? '';
                $title = $slide['title'] ?? '';
                $subtitle = $slide['subtitle'] ?? '';
                $text = $slide['text'] ?? '';
                $align = $slide['align'] ?? 'left';
                $button = $slide['button'] ?? null;
                $buttons = array_key_exists('buttons', $slide)
                    ? (is_array($slide['buttons']) ? $slide['buttons'] : [])
                    : (is_array($button) && $button !== [] ? [$button] : []);
                $buttons = array_values(array_filter($buttons, 'is_array'));
                $pattern = $slide['pattern'] ?? '';
                $pattern_args = $slide['args'] ?? [];
                $align = in_array($align, ['left', 'center', 'right'], true) ? $align : 'left';
                $animation_order = 0;
                ?>
                <li class="carousel__slide">
                    <?php if (is_string($bg_image) && $bg_image !== '') : ?>
                        <div class="carousel__bg">
                            <img src="<?php echo esc_url($bg_image); ?>" alt="" loading="lazy" />
                        </div>
                        <div class="carousel__overlay"></div>
                    <?php endif; ?>

                    <?php if ($title !== '' || $subtitle !== '' || $text !== '' || $buttons !== []) : ?>
                        <div class="carousel__content carousel__content--<?php echo esc_attr($align); ?>">
                            <?php if (is_scalar($subtitle) && $subtitle !== '') : ?>
                                <?php $animation_order++; ?>
                                <span
                                    class="carousel__subtitle carousel__animated-item"
                                    style="--carousel-animation-order: <?php echo (int) $animation_order; ?>"
                                ><?php echo esc_html((string) $subtitle); ?></span>
                            <?php endif; ?>

                            <?php if (is_scalar($title) && $title !== '') : ?>
                                <?php $animation_order++; ?>
                                <h2
                                    class="carousel__title carousel__animated-item"
                                    style="--carousel-animation-order: <?php echo (int) $animation_order; ?>"
                                ><?php echo esc_html((string) $title); ?></h2>
                            <?php endif; ?>

                            <?php if (is_string($text) && $text !== '') : ?>
                                <?php $animation_order++; ?>
                                <div
                                    class="carousel__text carousel__animated-item"
                                    style="--carousel-animation-order: <?php echo (int) $animation_order; ?>"
                                ><?php echo wp_kses_post($text); ?></div>
                            <?php endif; ?>

                            <?php if ($buttons !== []) : ?>
                                <?php $animation_order++; ?>
                                <div
                                    class="carousel__actions carousel__animated-item"
                                    style="--carousel-animation-order: <?php echo (int) $animation_order; ?>"
                                >
                                    <?php wp_site_core_render_pattern('button/button-group', [
                                        'buttons' => $buttons,
                                        'align' => $align,
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (is_string($pattern) && preg_match('#^patterns/[a-zA-Z0-9_/-]+$#', $pattern) && strpos($pattern, '..') === false) : ?>
                        <?php wp_site_core_render_pattern($pattern, is_array($pattern_args) ? $pattern_args : []); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php if ($has_controls) : ?>
        <button type="button" class="carousel__control carousel__control--prev" aria-label="<?php esc_attr_e('Diapositive précédente', 'wp-theme-test'); ?>">
            <span aria-hidden="true">&#10094;</span>
        </button>
        <button type="button" class="carousel__control carousel__control--next" aria-label="<?php esc_attr_e('Diapositive suivante', 'wp-theme-test'); ?>">
            <span aria-hidden="true">&#10095;</span>
        </button>
    <?php endif; ?>

    <?php if ($has_dots) : ?>
        <div class="carousel__dots" role="group" aria-label="<?php esc_attr_e('Choisir une diapositive', 'wp-theme-test'); ?>"></div>
    <?php endif; ?>

    <?php if ($has_autoplay && $show_pause) : ?>
        <button
            type="button"
            class="carousel__autoplay-toggle"
            aria-label="<?php esc_attr_e('Mettre le carrousel en pause', 'wp-theme-test'); ?>"
            aria-pressed="false"
        >
            <span class="carousel__autoplay-label"><?php esc_html_e('Pause', 'wp-theme-test'); ?></span>
        </button>
    <?php endif; ?>
</section>
