<?php
// /services/authService.php

// Garante que a sessão seja iniciada em qualquer lugar que este serviço for usado.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Os require_once podem ser movidos para dentro das funções para otimizar, 
// mas deixá-los aqui simplifica o entendimento.
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../dao/UsuarioDAO.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_token']);
}

function getLoggedUser(): ?Usuario {
    if (!isLoggedIn()) {
        return null;
    }
    
    if (isset($_SESSION['user_object'])) {
        return unserialize($_SESSION['user_object']);
    }

    $dao = new UsuarioDAO();
    $usuario = $dao->getByToken($_SESSION['user_token']);

    if ($usuario) {
        $_SESSION['user_object'] = serialize($usuario);
    }
    
    return $usuario;
}

function requireLogin(string $redirectUrl = '/sistema_vendas/pages/usuarios/login.php') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: " . $redirectUrl);
        exit();
    }
}

function isAdmin(): bool {
    $user = getLoggedUser();
    return $user && $user->isAdmin();
}

function requireAdmin() {
    requireLogin();
    
    // Se o usuário está logado, mas não é admin, redireciona para a home.
    if (!isAdmin()) {
        // Armazena uma mensagem de erro na sessão para ser exibida na página inicial.
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'message' => 'Acesso negado. Você não tem permissão para acessar esta página.'
        ];
        header('Location: /sistema_vendas/index.php');
        exit();
    }
}