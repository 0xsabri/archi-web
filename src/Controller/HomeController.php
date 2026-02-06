<?php

namespace App\Controller;

/**
 * HomeController
 * Gère la page d'accueil de l'API
 */
class HomeController
{
    /**
     * Page d'accueil de l'API
     * GET /
     * 
     * @return void
     */
    public function index(): void
    {
        echo json_encode([
            'status' => 'ok',
            'message' => 'API AdventureWorks ready',
            'version' => 'v1',
            'timestamp' => date('Y-m-d H:i:s'),
            'endpoints' => [
                'GET /' => 'Info API',
                'GET /employees' => 'Liste des employés',
                'GET /employees/{id}' => 'Détail d\'un employé'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}