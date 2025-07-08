<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();

require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Produto.php';
require_once __DIR__ . '/../../dao/ProdutoDAO.php';
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';
require_once __DIR__ . '/../../model/Usuario.php';
require_once __DIR__ . '/../../dao/UsuarioDAO.php';


// Pega o ID da URL e busca o produto
$id = $_GET['id'] ?? 0;
$produtoDAO = new ProdutoDAO();
$produto = $produtoDAO->getById($id);

// Se o produto não for encontrado, redireciona
if (!$produto) {
    header('Location: index.php');
    exit;
}

// Busca todas as categorias ativas para o <select>
$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->getAll(true);

require_once __DIR__ . '/../template/header.php';
?>

<h1>Editar Produto</h1>

<form action="acoes.php" method="POST">
    <input type="hidden" name="acao" value="editar">
    <input type="hidden" name="id" value="<?= $produto->getId() ?>">
    
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto->getNome()) ?>" required>
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto->getDescricao()) ?></textarea>
    </div>
    <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" value="<?= htmlspecialchars($produto->getPreco()) ?>" required>
    </div>
    <div class="form-group">
        <label for="categoria_id">Categoria:</label>
        <select id="categoria_id" name="categoria_id">
            <option value="">Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <?php $selected = ($produto->getCategoria() && $produto->getCategoria()->getId() == $categoria->getId()) ? 'selected' : ''; ?>
                <option value="<?= $categoria->getId() ?>" <?= $selected ?>><?= htmlspecialchars($categoria->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="ativo">Status:</label>
        <select id="ativo" name="ativo">
            <option value="1" <?= $produto->isAtivo() ? 'selected' : '' ?>>Ativo</option>
            <option value="0" <?= !$produto->isAtivo() ? 'selected' : '' ?>>Inativo</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php
require_once __DIR__ . '/../template/footer.php';
?>