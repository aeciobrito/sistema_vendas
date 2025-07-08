<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();
// Inclui os arquivos necessários
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';

// Simulação de ID de usuário logado (admin)
$usuario_logado_id = 1;

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';
$categoriaDAO = new CategoriaDAO();

switch ($acao) {
    case 'criar':
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
        $novaCategoria = new Categoria(null, $nome, $descricao);
        $categoriaDAO->create($novaCategoria, $usuario_logado_id);
        
        header('Location: index.php');
        exit;

    case 'editar':
        $id = $_POST['id'] ?? 0;
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $ativo = (bool)($_POST['ativo'] ?? 0);
        
        // Para o update, precisamos de um objeto Categoria completo
        // O ideal é buscar o objeto do banco para garantir que temos todos os dados
        $categoriaExistente = $categoriaDAO->getById($id);
        if ($categoriaExistente) {
             $categoriaAtualizada = new Categoria($id, $nome, $descricao, $ativo, $categoriaExistente->getDataCriacao(), null, null);
             $categoriaDAO->update($categoriaAtualizada, $usuario_logado_id);
        }
        
        header('Location: index.php');
        exit;

    case 'excluir':
        $id = $_POST['id'] ?? 0;
        // Usamos soft delete por segurança
        $categoriaDAO->softDelete($id, $usuario_logado_id);
        
        header('Location: index.php');
        exit;
}
?>