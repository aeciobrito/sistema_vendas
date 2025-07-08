<?php
// Inclui os arquivos necessários para buscar os produtos
require_once __DIR__ . '/model/Entidade.php';
require_once __DIR__ . '/model/Produto.php';
require_once __DIR__ . '/dao/ProdutoDAO.php';
// Incluídos para que o mapObject do ProdutoDAO funcione corretamente
require_once __DIR__ . '/model/Entidade.php';
require_once __DIR__ . '/model/Categoria.php';
require_once __DIR__ . '/dao/CategoriaDAO.php';
require_once __DIR__ . '/model/Usuario.php';
require_once __DIR__ . '/dao/UsuarioDAO.php';

$produtoDAO = new ProdutoDAO();
// Busca apenas os produtos ativos para exibir no catálogo
$produtos = $produtoDAO->getAll(true);

require_once __DIR__ . '/pages/template/header.php';

if (isset($_SESSION['flash_message'])) {
    $flash = $_SESSION['flash_message'];
    // Adiciona uma classe CSS baseada no tipo de mensagem (error, success, etc.)
    echo '<div class="flash-message ' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
    // Remove a mensagem da sessão para que não apareça novamente
    unset($_SESSION['flash_message']);
}
?>

<h1>Nosso Catálogo de Produtos</h1>
<p>Confira nossas ofertas especiais.</p>

<div class="product-grid">
    <?php foreach ($produtos as $produto): ?>
        <div class="card">
            <div class="card-image">
                <img src="<?= htmlspecialchars($produto->getImagemUrl()) ?>" alt="<?= htmlspecialchars($produto->getNome()) ?>">
            </div>
            <div class="card-body">
                <h2 class="card-title"><?= htmlspecialchars($produto->getNome()) ?></h2>
                <p class="card-price">R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?></p>
                <div class="card-actions">
                    <a href="/sistema_vendas/pages/produtos/detalhes.php?id=<?= $produto->getId() ?>" class="btn btn-secondary">Detalhes</a>
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
        </div>
    <?php endforeach; ?>

    <?php if (empty($produtos)): ?>
        <p>Nenhum produto encontrado no momento.</p>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/pages/template/footer.php';
?>