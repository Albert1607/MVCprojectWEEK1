<?php

namespace Core;

class Router
{
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $path, array $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Register a POST route
     */
    public function post(string $path, array $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Store route with regex for parameter matching
     */
    private function addRoute(string $method, string $path, array $callback): void
    {
        $path = '/' . trim($path, '/');
        
        // Convert route parameters like {id} to named regex capturing groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        $this->routes[$method][] = [
            'original_path' => $path,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }

    /**
     * Resolve the current request and dispatch to the target controller
     */
    public function resolve(Request $request): mixed
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route) {
                if (preg_match($route['pattern'], $path, $matches)) {
                    // Extract named parameters
                    $params = [];
                    foreach ($matches as $key => $value) {
                        if (!is_numeric($key)) {
                            $params[$key] = $value;
                        }
                    }

                    [$controllerClass, $action] = $route['callback'];

                    if (!class_exists($controllerClass)) {
                        http_response_code(500);
                        echo "Controller <strong>{$controllerClass}</strong> not found.";
                        return false;
                    }

                    $controller = new $controllerClass();

                    if (!method_exists($controller, $action)) {
                        http_response_code(500);
                        echo "Action <strong>{$action}</strong> not found in controller {$controllerClass}.";
                        return false;
                    }

                    // Call controller action passing request and any matched URL params
                    return call_user_func_array([$controller, $action], array_merge([$request], $params));
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        require_once __DIR__ . '/../app/Views/layouts/header.php';
        echo '<div class="card text-center my-5 p-5">
                <h1 class="display-4 text-danger font-bold">404</h1>
                <h2>Page Not Found</h2>
                <p class="text-muted">The route <code>' . htmlspecialchars($path) . '</code> was not recognized.</p>
                <div class="mt-4"><a href="/" class="btn btn-primary">Return Home</a></div>
              </div>';
        require_once __DIR__ . '/../app/Views/layouts/footer.php';
        return false;
    }
}
