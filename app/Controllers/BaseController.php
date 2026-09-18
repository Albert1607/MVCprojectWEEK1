<?php

namespace App\Controllers;

abstract class BaseController
{
    /**
     * Render a view file wrapped within the main layout
     */
    protected function view(string $view, array $data = []): void
    {
        // Extract variables for view template
        extract($data);

        // Flash message handling
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        // View paths
        $basePath = dirname(__DIR__) . '/Views/';
        $viewFile = $basePath . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("View template <code>{$viewFile}</code> does not exist.");
        }

        // Layout header
        require_once $basePath . 'layouts/header.php';

        // Content
        require $viewFile;

        // Layout footer
        require_once $basePath . 'layouts/footer.php';
    }

    /**
     * Redirect to another URL and terminate execution
     */
    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Store a one-time flash notification in session
     */
    protected function setFlash(string $type, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'] = [
            'type' => $type, // 'success', 'danger', 'warning', 'info'
            'message' => $message
        ];
    }

    /**
     * Output JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
