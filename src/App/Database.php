<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

/**
 * Database connection helper (PDO).
 *
 * - Centralise la création de PDO.
 * - Lit la configuration depuis .env puis .env.local (surcharge).
 * - Ne contient PAS de SQL métier : ce sera le rôle des Repositories (TD suivants).
 */
final class Database
{
    private static ?PDO $pdo = null;
    private static bool $envLoaded = false;

    public static function getConnection(): PDO
    {
        self::loadEnvOnce();

        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host = self::env('DB_HOST', '127.0.0.1');
        $port = self::env('DB_PORT', '3306');
        $db = self::env('DB_NAME', 'adwfull');
        $user = self::env('DB_USER', 'root');
        $pass = self::env('DB_PASS', '');
        $charset = self::env('DB_CHARSET', 'utf8mb4');

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $db, $charset);

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Erreur de connexion à la base : ' . $e->getMessage(), (int) $e->getCode());
        }

        return self::$pdo;
    }

    private static function env(string $key, ?string $default = null): ?string
    {
        // priorité : getenv() (variables OS / docker-compose env) puis $_ENV puis default
        $v = getenv($key);
        if ($v !== false) {
            return $v;
        }
        if (array_key_exists($key, $_ENV)) {
            return (string) $_ENV[$key];
        }
        return $default;
    }

    private static function loadEnvOnce(): void
    {
        if (self::$envLoaded) {
            return;
        }
        self::$envLoaded = true;

        $root = dirname(__DIR__, 2); // .../src/App -> racine projet
        self::loadEnvFile($root . '/.env');
        self::loadEnvFile($root . '/.env.local');
    }

    private static function loadEnvFile(string $filePath): void
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$k, $v] = explode('=', $line, 2);
            $k = trim($k);
            $v = trim($v);

            $v = preg_replace('/^([\'"])(.*)\\1$/', '$2', $v) ?? $v;

            if ($k === '') {
                continue;
            }

            // Ne pas écraser une variable déjà fournie par l’OS (docker-compose env)
            if (getenv($k) !== false) {
                continue;
            }

            $_ENV[$k] = $v;
            putenv($k . '=' . $v);
        }
    }
}
