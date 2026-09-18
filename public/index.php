<?php

/**
 * Front Controller - Single Entry Point
 * All HTTP requests pass through this file.
 */

// Start session for flash messaging and state
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple PSR-4 compatible class autoloader
spl_autoload_register(function ($class) {
    $prefixApp = 'App\\';
    $prefixCore = 'Core\\';

    $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;

    if (strncmp($prefixApp, $class, strlen($prefixApp)) === 0) {
        $relativeClass = substr($class, strlen($prefixApp));
        $file = $baseDir . 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    if (strncmp($prefixCore, $class, strlen($prefixCore)) === 0) {
        $relativeClass = substr($class, strlen($prefixCore));
        $file = $baseDir . 'core' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

use Core\Request;
use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\DoctorController;
use App\Controllers\AppointmentController;

$request = new Request();
$router = new Router();

// Define Application Routes
// 1. Dashboard / Home
$router->get('/', [HomeController::class, 'index']);

// 2. Doctors Directory & Details
$router->get('/doctors', [DoctorController::class, 'index']);
$router->get('/doctors/{id}', [DoctorController::class, 'show']);

// 3. Appointments Management & Booking
$router->get('/appointments', [AppointmentController::class, 'index']);
$router->get('/appointments/create', [AppointmentController::class, 'create']);
$router->post('/appointments', [AppointmentController::class, 'store']);
$router->post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
$router->post('/appointments/{id}/complete', [AppointmentController::class, 'complete']);

// Resolve incoming HTTP request
$router->resolve($request);
