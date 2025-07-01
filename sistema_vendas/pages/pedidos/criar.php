<?php
require_once '../core/Database.php';
require_once '../core/authService.php';
require_once '../dao/ClienteDAO.php';
require_once '../dao/FormaPagamentoDAO.php';
require_once '../dao/ProdutoDAO.php';
require_once '../dao/PedidoDAO.php';

$clientes = (new ClienteDAO())->getAll();
$formasPagamento = (new FormaPagamentoDAO())->getAll();
$produtos = (new ProdutoDAO())->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ClienteID' => $_POST['ClienteID'],
        'FormaPagamentoId' => $_POST['FormaPagamentoId'],
        'produtos' => []
    ];
    foreach ($_POST['produtos'] as $prodId => $qtd) {
        if (!empty($qtd) && $qtd > 0) {
            $data['produtos'][] = ['id' => $prodId, 'qtd' => $qtd];
        }
    }

    if (!empty($data['produtos'])) {
        $pedidoDAO = new PedidoDAO();
        if ($pedidoDAO->create($data)) {
            header('Location: listar.php');
            exit();
        }
    }
    $erro = "Erro ao criar pedido. Verifique se adicionou produtos.";
}
require_once '../templates/header.php';
?>
<h1>Novo Pedido</h1>
<?php if (isset($erro)) echo "<p>$erro</p>"; ?>
<form method="POST">
    <div>
        <label for="ClienteID">Cliente:</label>
        <select name="ClienteID" id="ClienteID" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= $cliente['Id'] ?>"><?= htmlspecialchars($cliente['Nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="FormaPagamentoId">Forma de Pagamento:</label>
        <select name="FormaPagamentoId" id="FormaPagamentoId" required>
            <?php foreach ($formasPagamento as $forma): ?>
                <option value="<?= $forma['Id'] ?>"><?= htmlspecialchars($forma['Nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <hr>
    <h3>Produtos</h3>
    <table>
        <thead><tr><th>Produto</th><th>Quantidade</th></tr></thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['Nome']) ?></td>
                <td><input type="number" name="produtos[<?= $produto['Id'] ?>]" min="0" value="0"></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <button type="submit">Finalizar Pedido</button>
</form>
<?php require_once '../templates/footer.php'; ?>