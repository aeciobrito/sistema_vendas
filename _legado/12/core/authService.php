<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';

session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['token']);
}

function getLoggedUser(): ?Usuario {
    if (!isLoggedIn()) return null;
    $dao = new UsuarioDAO();
    return $dao->getByToken($_SESSION['token']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}
