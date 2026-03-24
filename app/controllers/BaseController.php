<?php

namespace App\Controllers;

class BaseController
{
    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = dirname(__DIR__) . "/views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: {$view}");
        }
        
        require $viewPath;
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['usuario']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/');
        }
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
