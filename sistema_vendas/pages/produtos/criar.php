<?php
// produtos/criar.php
require_once '../core/Database.php';
require_once '../core/authService.php';
require_once '../dao/ProdutoDAO.php';
require_once '../dao/CategoriaDAO.php';

$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtoDAO = new ProdutoDAO();
    if ($produtoDAO->create($_POST)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao criar produto.";
}
require_once '../templates/header.php';
?>
<h1>Novo Produto</h1>
<?php if (isset($erro)) echo "<p>$erro</p>"; ?>
<form method="POST">
    <div><label for="Nome">Nome:</label><input type="text" name="Nome" id="Nome" required></div>
    <div><label for="Descricao">Descrição:</label><textarea name="Descricao" id="Descricao"></textarea></div>
    <div><label for="Preco">Preço:</label><input type="number" step="0.01" name="Preco" id="Preco" required></div>
    <div>
        <label for="CategoriaID">Categoria:</label>
        <select name="CategoriaID" id="CategoriaID">
            <option value="">Selecione</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['Id'] ?>"><?= htmlspecialchars($categoria['Nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit">Salvar</button>
</form>
<?php require_once '../templates/footer.php'; ?>