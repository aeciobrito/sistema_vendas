<?php

require_once '../core/Database.php';
require_once '../dao/CategoriaDAO.php';
$dao = new CategoriaDAO();
$categorias = $dao->getAll();
require_once '../templates/header.php';
?>
<div class="page-header">
    <h1>Categorias</h1>
    <a href="criar.php" class="btn-new">Nova Categoria</a>
</div>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Descrição</th></tr></thead>
    <tbody>
        <?php foreach ($categorias as $categoria): ?>
        <tr>
            <td><?= $categoria['Id'] ?></td>
            <td><?= htmlspecialchars($categoria['Nome']) ?></td>
            <td><?= htmlspecialchars($categoria['Descricao']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../templates/footer.php'; ?>