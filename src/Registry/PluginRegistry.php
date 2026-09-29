<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Registry;

use OSW3\WpSiteCore\Interfaces\ModuleInterface;

final class PluginRegistry
{
    /**
     * Récupère la liste de tous les modules enregistrés via le filtre.
     *
     * @return array<string, class-string<ModuleInterface>>
     */
    public function getAll(): array
    {
        /** @var array<string, class-string<ModuleInterface>> $modules */
        $modules = apply_filters('wp_site_core_register_modules', []);

        return is_array($modules) ? $modules : [];
    }

    /**
     * Vérifie si un module est enregistré.
     *
     * @param string $moduleId L'identifiant unique du module.
     * @return bool Vrai si le module est enregistré, faux sinon.
     */
    public function has(string $moduleId): bool
    {
        return isset($this->getAll()[$moduleId]);
    }
}