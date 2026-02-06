<?php

namespace App\Core;

/**
 * Router minimal pour la Séance 1
 * Gère les requêtes HTTP et renvoie des réponses JSON
 */
class Router
{
    /**
     * Traite la requête et retourne une réponse JSON
     * 
     * @return array Tableau associatif contenant la réponse
     */
    public function handleRequest(): array
    {
        return [
            'status' => 'ok',
            'message' => 'API AdventureWorks ready',
            'version' => 'v0',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}
