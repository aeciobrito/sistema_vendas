<?php
require_once __DIR__ . '/../core/authService.php';
requireLogin();
require_once __DIR__ . '/../dao/ProdutoDAO.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$dao = new ProdutoDAO();
if ($dao->delete($id)) {
    header('Location: listar.php');
    exit();
}
echo "Erro ao excluir produto.";
