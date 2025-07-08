<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();

require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';

// Pega o ID da URL e busca a categoria
$id = $_GET['id'] ?? 0;
$categoriaDAO = new CategoriaDAO();
$categoria = $categoriaDAO->getById($id);

// Se a categoria não for encontrada, redireciona
if (!$categoria) {
    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../template/header.php';
?>

<h1>Editar Categoria</h1>

<form action="acoes.php" method="POST">
    <input type="hidden" name="acao" value="editar">
    <input type="hidden" name="id" value="<?= $categoria->getId() ?>">
    
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($categoria->getNome()) ?>" required>
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($categoria->getDescricao()) ?></textarea>
    </div>
    <div class="form-group">
        <label for="ativo">Status:</label>
        <select id="ativo" name="ativo">
            <option value="1" <?= $categoria->isAtivo() ? 'selected' : '' ?>>Ativo</option>
            <option value="0" <?= !$categoria->isAtivo() ? 'selected' : '' ?>>Inativo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php
require_once __DIR__ . '/../template/footer.php';
?>