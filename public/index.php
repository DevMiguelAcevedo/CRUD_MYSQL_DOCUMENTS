<?php

// Configuración de errores y UTF-8
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Inicializar sesión
session_start();

use App\Router;
use App\Controllers\AuthController;
use App\Controllers\DocumentController;

// Crear router
$router = new Router();

// Rutas públicas
$router->get('/', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Rutas protegidas (documentos)
$router->get('/documentos', [DocumentController::class, 'index']);
$router->get('/documentos/crear', [DocumentController::class, 'create']);
$router->post('/documentos/guardar', [DocumentController::class, 'store']);
$router->get('/documentos/{id}/editar', [DocumentController::class, 'edit']);
$router->post('/documentos/{id}/actualizar', [DocumentController::class, 'update']);
$router->post('/documentos/{id}/eliminar', [DocumentController::class, 'delete']);
$router->get('/api/documentos/buscar', [DocumentController::class, 'search']);

// Ruta no encontrada
$router->notFound(function () {
    http_response_code(404);
    echo "Página no encontrada";
});

// Obtener método y URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Despachar
$router->dispatch($method, $uri);
