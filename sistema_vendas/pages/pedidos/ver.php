<?php
// pedidos/ver.php
require_once '../core/Database.php';
require_once '../dao/PedidoDAO.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit('ID inválido');
$dao = new PedidoDAO();
$pedido = $dao->getById($id);
if (!$pedido) exit('Pedido não encontrado');
$itens = $dao->getItensByPedidoId($id);
$total = 0;
require_once '../templates/header.php';
?>
<h1>Detalhes do Pedido #<?= $pedido['Id'] ?></h1>
<p><strong>Cliente:</strong> <?= htmlspecialchars($pedido['ClienteNome']) ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['DataPedido'])) ?></p>
<p><strong>Pagamento:</strong> <?= htmlspecialchars($pedido['FormaPagamentoNome']) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($pedido['Status']) ?></p>
<h3>Itens</h3>
<table>
    <thead><tr><th>Produto</th><th>Qtd</th><th>Preço Unit.</th><th>Subtotal</th></tr></thead>
    <tbody>
        <?php foreach ($itens as $item): 
            $subtotal = $item['Preco'] * $item['Quantidade'];
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($item['ProdutoNome']) ?></td>
            <td><?= $item['Quantidade'] ?></td>
            <td>R$ <?= number_format($item['Preco'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align: right;">Total do Pedido:</th>
            <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
        </tr>
    </tfoot>
</table>
<br>
<a href="listar.php">Voltar</a>
<?php require_once '../templates/footer.php'; ?>
