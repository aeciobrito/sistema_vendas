<?php
require_once '../core/Database.php';
require_once '../dao/PedidoDAO.php';
$dao = new PedidoDAO();
$pedidos = $dao->getAll();
require_once '../templates/header.php';
?>
<div class="page-header">
    <h1>Pedidos</h1>
    <a href="criar.php" class="btn-new">Novo Pedido</a>
</div>
<table>
    <thead><tr><th>ID</th><th>Cliente</th><th>Data</th><th>Pagamento</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido['Id'] ?></td>
            <td><?= htmlspecialchars($pedido['ClienteNome']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($pedido['DataPedido'])) ?></td>
            <td><?= htmlspecialchars($pedido['FormaPagamentoNome']) ?></td>
            <td><?= htmlspecialchars($pedido['Status']) ?></td>
            <td><a href="ver.php?id=<?= $pedido['Id'] ?>">Ver Detalhes</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once '../templates/footer.php'; ?>