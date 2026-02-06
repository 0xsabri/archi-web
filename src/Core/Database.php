<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe Database - Singleton pour la connexion PDO
 * Gère la connexion unique à la base de données
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Constructeur privé pour empêcher l'instanciation directe
     */
    private function __construct()
    {
    }

    /**
     * Récupère l'instance unique de la connexion PDO (singleton)
     * 
     * @return PDO Instance PDO
     * @throws PDOException Si la connexion échoue
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Charger les variables d'environnement
            Env::load();

            $host = Env::get('DB_HOST', 'localhost');
            $port = Env::get('DB_PORT', '3306');
            $dbname = Env::get('DB_NAME', 'adwfull');
            $user = Env::get('DB_USER', 'root');
            $password = Env::get('DB_PASSWORD', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new PDOException(
                    "Erreur de connexion à la base de données : " . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }

    /**
     * Empêche le clonage de l'instance
     */
    private function __clone()
    {
    }

    /**
     * Empêche la désérialisation de l'instance
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}
