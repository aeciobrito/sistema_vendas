<?php
require_once '../core/Database.php';
require_once '../dao/ProdutoDAO.php';
$dao = new ProdutoDAO();
$produtos = $dao->getAll();
require_once '../templates/header.php';
?>
<div class="page-header">
    <h1>Produtos</h1>
    <a href="criar.php" class="btn-new">Novo Produto</a>
</div>
<table>
    <thead><tr><th>Nome</th><th>Categoria</th><th>Preço</th><th>Ações</th></tr></thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto['Nome']) ?></td>
            <td><?= htmlspecialchars($produto['CategoriaNome'] ?? 'N/A') ?></td>
            <td>R$ <?= number_format($produto['Preco'], 2, ',', '.') ?></td>
            <td><a href="ver.php?id=<?= $produto['Id'] ?>">Ver</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../templates/footer.php'; ?>