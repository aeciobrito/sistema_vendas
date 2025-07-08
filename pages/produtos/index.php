<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin(); 
// Inclui os arquivos necessários
require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Produto.php';
require_once __DIR__ . '/../../dao/ProdutoDAO.php';
// Incluídos para que o mapObject do ProdutoDAO funcione
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';
require_once __DIR__ . '/../../model/Usuario.php';
require_once __DIR__ . '/../../dao/UsuarioDAO.php';


$produtoDAO = new ProdutoDAO();
// Busca todos os produtos (ativos e inativos) para gestão
$produtos = $produtoDAO->getAll(false);

// Inclui o cabeçalho
require_once __DIR__ . '/../template/header.php';
?>

<div class="page-header">
    <h1>Gestão de Produtos</h1>
    <a href="criar.php" class="btn btn-primary">Novo Produto</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto->getId()) ?></td>
            <td><?= htmlspecialchars($produto->getNome()) ?></td>
            <td>R$ <?= number_format($produto->getPreco(), 2, ',', '.') ?></td>
            <td><?= $produto->getCategoria() ? htmlspecialchars($produto->getCategoria()->getNome()) : 'Sem categoria' ?></td>
            <td><?= $produto->isAtivo() ? 'Ativo' : 'Inativo' ?></td>
            <td class="actions">
                <a href="editar.php?id=<?= $produto->getId() ?>" class="btn btn-secondary">Editar</a>
                <form action="acoes.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                    <input type="hidden" name="id" value="<?= $produto->getId() ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
// Inclui o rodapé
require_once __DIR__ . '/../template/footer.php';
?>