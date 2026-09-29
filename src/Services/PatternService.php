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

        // Récupère tous les fichiers .php dans /patterns/
        $files = glob($patterns_dir . '/*.php');

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
            ob_start();
            include $file;
            $content = ob_get_clean();

            // Nettoie l'en-tête PHP du rendu HTML
            $content = preg_replace('/^<\?php.*?\?>\s*/s', '', $content);

            // Enregistre le pattern auprès de WordPress
            register_block_pattern($headers['slug'], [
                'title'       => $headers['title'],
                'description' => $headers['description'] ?? '',
                'categories'  => array_map('trim', explode(',', $headers['categories'] ?? 'general')),
                'keywords'    => array_map('trim', explode(',', $headers['keywords'] ?? '')),
                'content'     => trim($content),
            ]);
        }
    }
}