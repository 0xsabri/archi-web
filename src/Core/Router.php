<?php

namespace App\Core;

/**
 * Router - Gestion des routes de l'API
 * Séance 2 : Ajout de routes dynamiques avec paramètres
 */
class Router
{
    private array $routes = [];

    /**
     * Enregistre une route GET
     */
    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Enregistre une route POST
     */
    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Traite la requête HTTP
     */
    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Extraire le path (sans query string)
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Retirer le base path si présent
        $basePath = $this->getBasePath();
        if ($basePath && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }
        
        // Assurer que le path commence par /
        $path = '/' . ltrim($path, '/');

        // Chercher une route correspondante
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routePath => $handler) {
                $params = $this->matchRoute($routePath, $path);
                
                if ($params !== false) {
                    $this->callHandler($handler, $params);
                    return;
                }
            }
        }

        // Aucune route trouvée
        $this->notFound($path);
    }

    /**
     * Vérifie si une route correspond au path et extrait les paramètres
     */
    private function matchRoute(string $routePath, string $requestPath): array|false
    {
        // Route exacte
        if ($routePath === $requestPath) {
            return [];
        }

        // Route avec paramètres
        $routePattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';

        if (preg_match($routePattern, $requestPath, $matches)) {
            array_shift($matches);
            
            // Extraire les noms des paramètres
            preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
            $params = [];
            
            foreach ($paramNames[1] as $index => $name) {
                $params[$name] = $matches[$index] ?? null;
            }
            
            return $params;
        }

        return false;
    }

    /**
     * Appelle le handler de la route
     */
    private function callHandler(array $handler, array $params): void
    {
        [$controllerClass, $method] = $handler;
        
        $controller = new $controllerClass();
        
        if (empty($params)) {
            $controller->$method();
        } else {
            $controller->$method(...array_values($params));
        }
    }

    /**
     * Calcule le base path à partir de SCRIPT_NAME
     */
    private function getBasePath(): string
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        // Retire /index.php pour garder /public dans le basePath
        $basePath = preg_replace('#/index\.php$#', '', $script) ?? '';
        return rtrim($basePath, '/');
    }

    /**
     * Gère les routes non trouvées (404)
     */
    private function notFound(string $path): void
    {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Route non trouvée',
            'path' => $path
        ], JSON_PRETTY_PRINT);
    }
}