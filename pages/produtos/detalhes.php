<?php
// Inclui os arquivos necessários
require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Produto.php';
require_once __DIR__ . '/../../dao/ProdutoDAO.php';
// Dependências para o mapObject do ProdutoDAO
require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';
require_once __DIR__ . '/../../model/Usuario.php';
require_once __DIR__ . '/../../dao/UsuarioDAO.php';

// Pega o ID da URL e busca o produto
$id = $_GET['id'] ?? 0;
$produtoDAO = new ProdutoDAO();
$produto = $produtoDAO->getById($id);

// Inclui o cabeçalho
require_once __DIR__ . '/../template/header.php';

if ($produto && $produto->isAtivo()):
?>
    <style>
        .product-details { display: flex; gap: 2rem; }
        .product-image { flex: 1; }
        .product-image img { max-width: 100%; border-radius: 8px; }
        .product-info { flex: 1; }
        .product-info .price { font-size: 2rem; color: #007bff; margin: 1rem 0; }
        .product-info .category { font-style: italic; color: #6c757d; margin-bottom: 1rem; }
    </style>

    <div class="product-details">
        <div class="product-image">
            <img src="<?= htmlspecialchars($produto->getImagemUrl()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
        </div>
        <div class="product-info">
            <h1><?= htmlspecialchars($produto->getNome()) ?></h1>
            
            <?php if ($produto->getCategoria()): ?>
                <p class="category">Categoria: <?= htmlspecialchars($produto->getCategoria()->getNome()) ?></p>
            <?php endif; ?>

            <p class="price">R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?></p>
            
            <h2>Descrição</h2>
            <p><?= nl2br(htmlspecialchars($produto->getDescricao())) ?></p>

            <br>

            <button class="btn btn-primary" onclick="adicionarAoCarrinho(
                <?= $produto->getId() ?>,
                '<?= htmlspecialchars(addslashes($produto->getNome())) ?>',
                <?= $produto->getPreco() ?>,
                '<?= htmlspecialchars($produto->getImagemUrl()) ?>'
            )">
                Adicionar ao Carrinho
            </button>
        </div>
    </div>

<?php else: ?>
    <h1>Produto não encontrado</h1>
    <p>O produto que você está procurando não existe ou não está mais disponível.</p>
    <a href="/sistema_vendas/index.php" class="btn btn-primary">Voltar ao Catálogo</a>
<?php
endif;

// Inclui o rodapé
require_once __DIR__ . '/../template/footer.php';
?>