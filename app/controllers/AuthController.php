<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    // Usuario y contraseña por defecto (sin base de datos)
    private const USUARIO = 'admin';
    private const PASSWORD = 'admin123';

    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/documentos');
        }
        
        $this->view('login', [
            'titulo' => 'Login - CRUD Documentos'
        ]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($usuario === self::USUARIO && $password === self::PASSWORD) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['login_time'] = time();
            $this->redirect('/documentos');

        } else {
            $this->view('login', [
                'titulo' => 'Login - CRUD Documentos',
                'error' => 'Usuario o contraseña inválidos'
            ]);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }
}
