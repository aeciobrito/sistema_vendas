<?php
// /services/authService.php

// Garante que a sessão seja iniciada em qualquer lugar que este serviço for usado.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/Entidade.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../dao/UsuarioDAO.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_token']);
}

function getLoggedUser(): ?Usuario {
    if (!isLoggedIn()) {
        return null;
    }
    
    // Para evitar buscas repetidas no banco, podemos usar a sessão para cachear o objeto
    if (isset($_SESSION['user_object'])) {
        return unserialize($_SESSION['user_object']);
    }

    $dao = new UsuarioDAO();
    $usuario = $dao->getByToken($_SESSION['user_token']);

    if ($usuario) {
        // Armazena o objeto na sessão para otimizar
        $_SESSION['user_object'] = serialize($usuario);
    }
    
    return $usuario;
}

function requireLogin(string $redirectUrl = '/sistema_vendas/pages/usuarios/login.php') {
    if (!isLoggedIn()) {
        // Salva a URL que o usuário tentou acessar para redirecioná-lo de volta após o login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: " . $redirectUrl);
        exit();
    }
}