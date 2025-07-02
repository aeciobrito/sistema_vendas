<?php

require_once '../core/Database.php';
require_once '../dao/ClienteDAO.php';
$dao = new ClienteDAO();
$clientes = $dao->getAll();
require_once '../templates/header.php';
?>
<div class="page-header">
    <h1>Clientes</h1>
    <a href="criar.php" class="btn-new">Novo Cliente</a>
</div>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th></tr></thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td><?= $cliente['Id'] ?></td>
            <td><?= htmlspecialchars($cliente['Nome']) ?></td>
            <td><?= htmlspecialchars($cliente['Email']) ?></td>
            <td><?= htmlspecialchars($cliente['Telefone']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../templates/footer.php'; ?>