<?php
require_once '../core/Database.php';
require_once '../dao/ProdutoDAO.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit('ID inválido');
$dao = new ProdutoDAO();
$produto = $dao->getById($id);
if (!$produto) exit('Produto não encontrado');
require_once '../templates/header.php';
?>
<h1><?= htmlspecialchars($produto['Nome']) ?></h1>
<p><strong>Descrição:</strong> <?= htmlspecialchars($produto['Descricao']) ?></p>
<p><strong>Categoria:</strong> <?= htmlspecialchars($produto['CategoriaNome'] ?? 'N/A') ?></p>
<p><strong>Preço:</strong> R$ <?= number_format($produto['Preco'], 2, ',', '.') ?></p>
<a href="listar.php">Voltar</a>
<?php require_once '../templates/footer.php'; ?>