<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();

require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Categoria.php';
require_once __DIR__ . '/../../dao/CategoriaDAO.php';

$categoriaDAO = new CategoriaDAO();
// Busca todas as categorias (ativas e inativas) para gestão
$categorias = $categoriaDAO->getAll(false);

require_once __DIR__ . '/../template/header.php';
?>

<div class="page-header">
    <h1>Gestão de Categorias</h1>
    <a href="criar.php" class="btn btn-primary">Nova Categoria</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $categoria): ?>
        <tr>
            <td><?= htmlspecialchars($categoria->getId()) ?></td>
            <td><?= htmlspecialchars($categoria->getNome()) ?></td>
            <td><?= $categoria->isAtivo() ? 'Ativo' : 'Inativo' ?></td>
            <td class="actions">
                <a href="editar.php?id=<?= $categoria->getId() ?>" class="btn btn-secondary">Editar</a>
                <form action="acoes.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                    <input type="hidden" name="id" value="<?= $categoria->getId() ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once __DIR__ . '/../template/footer.php';
?>