<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, array $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'handler' => $handler
        ];
    }

    public function dispatch(Request $request): void
    {
        $requestMethod = $request->getMethod();
        $requestUri = rtrim($request->getUri(), '/');
        if ($requestUri === '') {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . rtrim($pattern, '/') . '$#';
            if ($route['path'] === '/') {
                $pattern = '#^/$#';
            }

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                [$controllerClass, $action] = $route['handler'];
                $controller = $this->container->get($controllerClass);

                call_user_func_array([$controller, $action], array_merge([$request], $params));
                return;
            }
        }

        Response::error('Endpoint route tidak ditemukan (404 Not Found)', 404);
    }
}