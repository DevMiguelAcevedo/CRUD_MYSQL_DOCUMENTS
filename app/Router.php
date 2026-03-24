<?php

namespace App;

class Router
{
    private $routes = [];
    private $notFoundCallback;

    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
    }

    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    private function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function resolve($method, $uri)
    {
        // Limpiar la URI de parámetros GET
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Remover la carpeta base si existe (para funcionamiento en subdirectorio)
        $basePath = '/CRUD_MYSQL_DOCUMENTS/public';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Asegurar que comience con /
        if (empty($path)) {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path, $matches)) {
                return [
                    'callback' => $route['callback'],
                    'params' => $matches
                ];
            }
        }

        if ($this->notFoundCallback) {
            return [
                'callback' => $this->notFoundCallback,
                'params' => []
            ];
        }

        http_response_code(404);
        return null;
    }

    private function matchPath($pattern, $path, &$matches)
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $path, $matches);
    }

    public function dispatch($method, $uri)
    {
        $route = $this->resolve($method, $uri);
        
        if ($route === null) {
            return;
        }

        $callback = $route['callback'];
        $params = array_filter($route['params'], 'is_string', ARRAY_FILTER_USE_KEY);

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $method = $callback[1];
            return $controller->$method(...array_values($params));
        } elseif (is_callable($callback)) {
            return call_user_func_array($callback, array_values($params));
        }
    }
}
