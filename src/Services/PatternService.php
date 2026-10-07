<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Services;

final class PatternService
{
    public static function register(string $patterns_dir)
    {
        if (!is_dir($patterns_dir)) {
            return;
        }
        
        // 1. Interception du rendu pour traiter les args du wp:pattern
        add_filter('pre_render_block', [self::class, 'renderPatternBlock'], 10, 2);

        // Récupère tous les fichiers .php dans /patterns/
        $files = array_merge([], 
            glob($patterns_dir . '/*.php'),
            glob($patterns_dir . '/*/*.php'),
        );
        $files = array_map('realpath', $files);

        // var_dump( scandir($patterns_dir));
        // var_dump( $files );

        foreach ($files as $file) {
            // Extrait les en-têtes du fichier (Title, Slug, Categories, etc.)
            $headers = get_file_data($file, [
                'title'       => 'Title',
                'slug'        => 'Slug',
                'categories'  => 'Categories',
                'description' => 'Description',
                'keywords'    => 'Keywords',
            ]);

            if (empty($headers['title']) || empty($headers['slug'])) {
                continue;
            }

            // Obtient le contenu du bloc (en ignorant les balises PHP de l'en-tête)
            // ob_start();
            // include $file;
            // $content = ob_get_clean();

            // Nettoie l'en-tête PHP du rendu HTML
            // $content = preg_replace('/^<\?php.*?\? >\s*/s', '', $content);

            // Enregistre le pattern auprès de WordPress
            register_block_pattern($headers['slug'], [
                'title'       => $headers['title'],
                'description' => $headers['description'] ?? '',
                'categories'  => array_map('trim', explode(',', $headers['categories'] ?? 'general')),
                'keywords'    => array_map('trim', explode(',', $headers['keywords'] ?? '')),
                // 'content'     => trim($content),
                'content'     => '<!-- dynamic component -->',
            ]);
        }
    }

    public static function renderPatternBlock($pre_render, array $block)
    {
        $allowed_blocks = ['core/pattern', 'wp-site-core/component'];
        if (!in_array($block['blockName'] ?? '', $allowed_blocks, true)) {
            return $pre_render;
        }

        $slug = $block['attrs']['slug'] ?? '';
        if (!$slug) {
            return $pre_render;
        }

        $clean_slug = preg_replace('/^(wp-site-core\/)?(component\/)?/', '', $slug);
        $parent_dir = explode('-', $clean_slug)[0];

        // Recherche du fichier dans le plugin / thème
        $possible_paths = [
            // --- 1. OVERRIDES THÈME (Prioritaires si le fichier existe) ---
            get_theme_file_path("patterns/{$parent_dir}/{$clean_slug}.php"),
            get_theme_file_path("patterns/{$clean_slug}/{$clean_slug}.php"),
            get_theme_file_path("patterns/{$clean_slug}.php"),

            // --- 2. FICHIERS NATIVE PLUGIN (Fallback) ---
            __DIR__ . "/../../patterns/{$parent_dir}/{$clean_slug}.php",
            __DIR__ . "/../../patterns/{$clean_slug}/{$clean_slug}.php",
            __DIR__ . "/../../patterns/{$clean_slug}.php",
        ];

        $file_path = false;
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $file_path = $path;
                break;
            }
        }

        if (!$file_path) {
            return $pre_render;
        }

        $args = $block['attrs']['args'] ?? [];

        ob_start();
        include $file_path;
        return ob_get_clean();
    }
}