<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin(); // Ação exclusiva para administradores

$acao = $_POST['acao'] ?? '';
$id = (int)($_POST['id'] ?? 0);
$admin_logado_id = getLoggedUser()->getId();

if (!$id || !$acao) {
    header('Location: usuarios.php');
    exit;
}

// Proteção contra auto-modificação
if ($id === $admin_logado_id) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Você não pode modificar seu próprio status.'];
    header('Location: usuarios.php');
    exit;
}

$usuarioDAO = new UsuarioDAO();
$usuario = $usuarioDAO->getById($id);

if (!$usuario) {
    header('Location: usuarios.php');
    exit;
}

switch ($acao) {
    case 'toggle_admin':
        // Inverte o status de admin do usuário
        $novoStatusAdmin = !$usuario->isAdmin();
        $usuarioAtualizado = new Usuario($usuario->getId(), $usuario->getNomeCompleto(), $usuario->getNomeUsuario(), $usuario->getSenha(), $usuario->getEmail(), $usuario->getTelefone(), $usuario->getCpf(), $novoStatusAdmin, $usuario->isAtivo(), $usuario->getToken());
        $usuarioDAO->update($usuarioAtualizado, $admin_logado_id);
        break;

    case 'toggle_status':
        // Inverte o status de ativo/inativo do usuário (soft delete/restore)
        $novoStatusAtivo = !$usuario->isAtivo();
        $usuarioAtualizado = new Usuario($usuario->getId(), $usuario->getNomeCompleto(), $usuario->getNomeUsuario(), $usuario->getSenha(), $usuario->getEmail(), $usuario->getTelefone(), $usuario->getCpf(), $usuario->isAdmin(), $novoStatusAtivo, $usuario->getToken());
        $usuarioDAO->update($usuarioAtualizado, $admin_logado_id);
        break;
}

header('Location: usuarios.php');
exit;   