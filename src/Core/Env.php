<?php

namespace App\Core;

/**
 * Classe Env - Lecture des variables d'environnement depuis .env
 * Charge automatiquement le fichier .env à la racine du projet
 */
class Env
{
    /**
     * Charge le fichier .env et définit les variables d'environnement
     * 
     * @param string $path Chemin vers le fichier .env
     * @return void
     */
    public static function load(string $path = __DIR__ . '/../../.env'): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignorer les commentaires
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            // Parser la ligne KEY=VALUE
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Définir la variable d'environnement
                if (!getenv($key)) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }
    }

    /**
     * Récupère une variable d'environnement
     * 
     * @param string $key Nom de la variable
     * @param mixed $default Valeur par défaut si non trouvée
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        
        if ($value === false) {
            return $default;
        }

        return $value;
    }
}
