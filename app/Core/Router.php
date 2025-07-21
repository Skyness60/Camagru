<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];
    private $notFoundHandler;

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    public function setNotFoundHandler(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$this->normalizePath($path)] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalizePath(parse_url($uri, PHP_URL_PATH) ?? '/');
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler) {
            $handler();
            return;
        }

        // Try matching with parameters
        foreach ($this->routes[$method] ?? [] as $route => $routeHandler) {
            $params = $this->matchWithParams($route, $path);
            if ($params !== null) {
                $routeHandler(...$params);
                return;
            }
        }

        if (isset($this->notFoundHandler)) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }

    private function normalizePath(string $path): string
    {
        return '/' . trim($path, '/');
    }

    private function matchWithParams(string $route, string $path): ?array
    {
        $routeParts = explode('/', trim($route, '/'));
        $pathParts = explode('/', trim($path, '/'));

        if (count($routeParts) !== count($pathParts)) {
            return null;
        }

        $params = [];
        foreach ($routeParts as $i => $part) {
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $params[] = $pathParts[$i];
            } elseif ($part !== $pathParts[$i]) {
                return null;
            }
        }
        return $params;
    }
}