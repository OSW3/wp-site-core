<?php
declare(strict_types=1);
namespace OSW3\WpSiteCore\Config;

final class Options
{
    private const OPTION_KEY = 'wp_site_core_options';

    /**
     * Cache local en mémoire pour éviter les get_option répétitifs.
     */
    private array $data = [];

    public function __construct()
    {
        $this->load();
    }

    /**
     * Charge la configuration depuis la base de données.
     */
    public function load(): void
    {
        $stored = get_option(self::OPTION_KEY, []);
        $this->data = is_array($stored) ? $stored : [];
    }

    /**
     * Récupère une option avec valeur par défaut si absente.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Définit ou met à jour une option et enregistre en BDD.
     */
    public function set(string $key, mixed $value): bool
    {
        $this->data[$key] = $value;
        return update_option(self::OPTION_KEY, $this->data);
    }

    /**
     * Supprime une clé de configuration.
     */
    public function delete(string $key): bool
    {
        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
            return update_option(self::OPTION_KEY, $this->data);
        }

        return false;
    }

    /**
     * Récupère l'ensemble de la carte d'options.
     */
    public function all(): array
    {
        return $this->data;
    }
}