<?php
declare(strict_types=1);

namespace App\Http;

class Router
{
    /**
     * @var array<string, array<string, callable>>
     */
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Register a POST route
     */
    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Register a PATCH route
     */
    public function patch(string $path, callable $handler): void
    {
        $this->routes['PATCH'][$path] = $handler;
    }

    /**
     * Register a DELETE route
     */
    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    /**
     * Get the HTTP method, accounting for method spoofing via _method
     */
    private function getMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Method spoofing for PATCH/DELETE using _method hidden input
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofedMethod = strtoupper($_POST['_method']);
            if (in_array($spoofedMethod, ['PATCH', 'DELETE'], true)) {
                $method = $spoofedMethod;
            }
        }

        return $method;
    }

    /**
     * Dispatch the request to the matching route handler
     */
    public function dispatch(): void
    {
        $method = $this->getMethod();
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
            return;
        }

        // 404: Not Found
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        echo "<p>The page you requested does not exist.</p>";
    }

    /**
     * Check if a route exists
     */
    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[$method][$path]);
    }
}
