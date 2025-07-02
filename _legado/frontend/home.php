<?php
require_once("template/header.php");
require_once("../backend/entity/Produto.php");
require_once("../backend/dao/ProdutoDAO.php");

$produtoDAO = new ProdutoDAO();
$produtos = $produtoDAO->getAll();

$produtosMock = [
    new Produto(null, 'Produto XYZ1', 'Mock produto 1', 10.00, 1, null, null, 1, 1),
    new Produto(null, 'Produto ABC2', 'Mock produto 2', 20.00, 1, null, null, 1, 1),
    new Produto(null, 'Produto ABC3', 'Mock produto 3', 30.00, 1, null, null, 1, 1),
    new Produto(null, 'Produto ABC4', 'Mock produto 4', 40.00, 1, null, null, 1, 1),
    new Produto(null, 'Produto ABC5', 'Mock produto 5', 50.00, 1, null, null, 1, 1)
];
?>

<div class="container">    
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($produtos as $produto) : ?>
            <div class="col">
                <div class="card">
                    <img src="img/produtos.webp" class="card-img-top" alt="produtos">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($produto->getNome(), ENT_QUOTES, 'UTF-8'); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($produto->getDescricao(), ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="card-text"><strong>Preço: </strong>R$<?php echo number_format($produto->getPreco(), 2, ',', '.'); ?></p>
                        <a href="produtoDetalhes.php?id=<?php echo $produto->getId(); ?>" class="btn btn-primary">Detalhes</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require_once("template/footer.php");
?>