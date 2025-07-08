<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();
require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';
require_once __DIR__ . '/../../model/Categoria.php';

// Busca todas as categorias ativas para preencher o <select>
$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->getAll(true);

require_once __DIR__ . '/../template/header.php';
?>

<h1>Novo Produto</h1>

<form action="acoes.php" method="POST">
    <input type="hidden" name="acao" value="criar">
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"></textarea>
    </div>
    <div class="form-group">
        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="categoria_id">Categoria:</label>
        <select id="categoria_id" name="categoria_id">
            <option value="">Selecione uma categoria</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria->getId() ?>"><?= htmlspecialchars($categoria->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php
require_once __DIR__ . '/../template/footer.php';
?>