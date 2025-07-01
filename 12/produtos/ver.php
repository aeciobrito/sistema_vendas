<?php
require_once __DIR__ . '/../dao/ProdutoDAO.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit("ID de produto inválido.");
}

$dao = new ProdutoDAO();
$produto = $dao->getById($id);

if (!$produto) {
    echo "Produto não encontrado.";
    exit();
}
?>
<h1><?= htmlspecialchars($produto->getNome()) ?></h1>

<?php if ($produto->getFornecedor()): ?>
    <p>
        <strong>Fornecedor:</strong> 
        <!-- O link para os detalhes do fornecedor ainda não existe, mas podemos prepará-lo -->
        <a href="../fornecedores/ver.php?id=<?= $produto->getFornecedor()->getId() ?>">
            <?= htmlspecialchars($produto->getFornecedor()->getNome()) ?>
        </a>
    </p>
<?php else: ?>
    <p><strong>Fornecedor:</strong> Não informado</p>
<?php endif; ?>

<p><strong>Preço:</strong> R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?></p>
<p><strong>Ativo:</strong> <?= $produto->getAtivo() ? 'Sim' : 'Não' ?></p>
<p><strong>Validade:</strong> <?= $produto->getDataDeValidade() ? date('d/m/Y', strtotime($produto->getDataDeValidade())) : 'Sem validade' ?></p>
<p><strong>Data de Cadastro:</strong> <?= date('d/m/Y', strtotime($produto->getDataDeCadastro())) ?></p>

<br>
<a href="listar.php">Voltar para a lista de produtos</a>
