<?php
/**
 * Title: Cover
 * Description: Cover component for displaying a full-width cover section with background media and overlay.
 * Categories: components, status
 * Keywords: cover, background, media, overlay, section
 * Slug: wp-site-core/component/cover
 * Block Types: wp-site-core/component
 * Version: 1.0
 * Since: 1.0
 * Tested up to: 1.0
 * License: GPL-2.0-or-later
 * Text Domain: osw3-wp-site-core
 */

// 1. Initialization & fallbacks
$args = $args ?? [];


$report_invalid = static function (string $name): void {
    _doing_it_wrong('component/cover', sprintf('Invalid Cover argument: %s.', $name), '1.0.0');
};

$read_string = static function (string $name, string $default = '') use ($args, $report_invalid): string {
    $value = $args[$name] ?? $default;
    if (!is_string($value)) {
        $report_invalid($name);
        return $default;
    }
    return $value;
};
$read_choice = static function (string $name, array $choices, string $default) use ($read_string, $report_invalid): string {
    $value = $read_string($name, $default);
    if (!in_array($value, $choices, true)) {
        $report_invalid($name);
        return $default;
    }
    return $value;
};
$read_number = static function (string $name, float $default, float $min, float $max) use ($args, $report_invalid): float {
    $value = $args[$name] ?? $default;
    if (!is_numeric($value) || !is_finite((float) $value)) {
        $report_invalid($name);
        return $default;
    }
    return min($max, max($min, (float) $value));
};
$read_color = static function (string $name, string $default) use ($read_string, $report_invalid): string {
    $value = sanitize_hex_color($read_string($name, $default));
    if (!$value) {
        $report_invalid($name);
        return $default;
    }
    return $value;
};

// 2. Read and validate cover arguments
$title = $read_string('title');
$subtitle = $read_string('subtitle');
$description = $read_string('description');
$bg_image = $read_string('bg_image');
$bg_video = $read_string('bg_video');
$poster = $read_string('poster', $bg_image);
$fallback_image = $bg_image !== '' ? $bg_image : $poster;
$min_height = $read_string('min_height', '400px');
if (!preg_match('/^(?:0|(?:\d+(?:\.\d+)?|\.\d+)(?:px|rem|em|vh|svh|dvh|vw|%))$/', $min_height)) {
    $report_invalid('min_height');
    $min_height = '400px';
}
$align = $read_choice('align', ['left', 'center', 'right'], 'center');
$vertical_align = $read_choice('vertical_align', ['top', 'center', 'bottom'], 'center');
$heading_level = filter_var($args['heading_level'] ?? 1, FILTER_VALIDATE_INT);
if ($heading_level === false || $heading_level < 1 || $heading_level > 6) {
    $report_invalid('heading_level');
    $heading_level = 1;
}
$image_id = filter_var($args['image_id'] ?? 0, FILTER_VALIDATE_INT);
if ($image_id === false || $image_id < 0) {
    $report_invalid('image_id');
    $image_id = 0;
}
$priority = filter_var($args['priority'] ?? true, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
if ($priority === null) {
    $report_invalid('priority');
    $priority = true;
}
$buttons = $args['buttons'] ?? [];
if (!is_array($buttons)) {
    $report_invalid('buttons');
    $buttons = [];
}

// 3. Generate a unique ID for the cover section
$cover_id = wp_unique_id('cover-');

// 4. Prepare inline styles for the cover section
$styles = [
    '--cover-min-height: ' . $min_height,
    '--cover-overlay-opacity: ' . ($read_number('overlay_opacity', 50, 0, 100) / 100),
    '--cover-overlay-color: ' . $read_color('overlay_color', '#000000'),
    '--cover-text-color: ' . $read_color('text_color', '#ffffff'),
    '--cover-background-color: ' . $read_color('background_color', '#212529'),
    '--cover-object-position: ' . $read_number('focal_x', 50, 0, 100) . '% ' . $read_number('focal_y', 50, 0, 100) . '%',
];

// 7. Prepare CSS classes for the cover container
$classes = [
    'cover',
    "cover--align-{$align}",
    "cover--vertical-{$vertical_align}",
];
?>
<section
    class="<?php echo esc_attr(implode(' ', $classes)); ?>"
    style="<?php echo esc_attr(implode('; ', $styles)); ?>;"
    data-component="cover"
    <?php if ($title !== '') : ?>aria-labelledby="<?php echo esc_attr($cover_id . '-title'); ?>"<?php endif; ?>
>
    <div class="cover__media" aria-hidden="true">
        <?php if ($image_id > 0) : ?>
            <?php
            $image = wp_get_attachment_image($image_id, 'full', false, [
                'class' => 'cover__image',
                'alt' => '',
                'loading' => $priority ? 'eager' : 'lazy',
                'fetchpriority' => $priority ? 'high' : 'auto',
                'sizes' => $read_string('sizes', '100vw'),
            ]);
            if ($image === '') {
                $report_invalid('image_id: no attachment image found');
            }
            echo $image;
            ?>
        <?php elseif ($fallback_image !== '') : ?>
            <img
                class="cover__image"
                src="<?php echo esc_url($fallback_image); ?>"
                alt=""
                loading="<?php echo $priority ? 'eager' : 'lazy'; ?>"
                fetchpriority="<?php echo $priority ? 'high' : 'auto'; ?>"
            />
        <?php endif; ?>

        <?php if ($bg_video !== '') : ?>
            <video
                id="<?php echo esc_attr($cover_id . '-video'); ?>"
                class="cover__video"
                loop muted playsinline preload="none" tabindex="-1" hidden
                <?php if ($poster !== '') : ?>poster="<?php echo esc_url($poster); ?>"<?php endif; ?>
            >
                <source src="<?php echo esc_url($bg_video); ?>" type="video/mp4">
            </video>
        <?php endif; ?>

        <div class="cover__overlay"></div>
    </div>

    <div class="cover__content">
        <div class="cover__container">
            <?php if ($subtitle !== '') : ?>
                <span class="cover__subtitle"><?php echo esc_html($subtitle); ?></span>
            <?php endif; ?>

            <?php if ($title !== '') : ?>
                <h<?php echo (int) $heading_level; ?> id="<?php echo esc_attr($cover_id . '-title'); ?>" class="cover__title"><?php echo esc_html($title); ?></h<?php echo (int) $heading_level; ?>>
            <?php endif; ?>

            <?php if ($description !== '') : ?>
                <div class="cover__description"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>

            <?php if (!empty($buttons)) : ?>
                <div class="cover__actions">
                    <?php foreach ($buttons as $button) : ?>
                        <?php
                        if (!is_array($button)) {
                            $report_invalid('buttons: expected button arguments');
                            continue;
                        }
                        ?>
                        <?php wp_site_core_render_pattern('button/button', $button); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($bg_video !== '') : ?>
        <button
            type="button"
            class="cover__video-toggle"
            aria-controls="<?php echo esc_attr($cover_id . '-video'); ?>"
            hidden
        ><?php esc_html_e('Lire la vidéo', 'wp-theme-test'); ?></button>
        <p class="cover__video-status" role="status" hidden></p>
    <?php endif; ?>
</section>