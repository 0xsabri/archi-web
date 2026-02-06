<?php

/**
 * Front Controller
 * Point d'entrée unique de l'application API
 * Séance 2 - Architecture Web 2026
 */

// Chargement de l'autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Env;
use App\Controller\HomeController;
use App\Controller\EmployeeController;

// Charger les variables d'environnement
Env::load();

// Configuration des headers HTTP pour JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Gestion des requêtes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Instanciation du router
    $router = new Router();
    
    // Définition des routes
    
    // Route de test / bienvenue
    $router->get('/', [HomeController::class, 'index']);
    
    // Routes employees
    $router->get('/employees', [EmployeeController::class, 'list']);
    $router->get('/employees/{id}', [EmployeeController::class, 'show']);
    
    // Traitement de la requête
    $router->handleRequest();
    
} catch (Exception $e) {
    // Gestion des erreurs globales
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal Server Error',
        'error' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}