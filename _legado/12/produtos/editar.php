<?php
require_once __DIR__ . '/../core/authService.php';
requireLogin();
require_once __DIR__ . '/../dao/ProdutoDAO.php';
require_once __DIR__ . '/../dao/FornecedorDAO.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$produtoDAO = new ProdutoDAO();
$produto = $produtoDAO->getById($id);
if (!$produto) exit("Produto não encontrado");

$fornecedorDAO = new FornecedorDAO();
$fornecedores = $fornecedorDAO->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fornecedorId = filter_input(INPUT_POST, 'fornecedor_id', FILTER_VALIDATE_INT);
    $fornecedor = $fornecedorId ? $fornecedorDAO->getById($fornecedorId) : null;

    // Recria o objeto produto com os novos dados
    $produtoAtualizado = new Produto(
        $id,
        $_POST['nome'],
        (float) $_POST['preco'],
        isset($_POST['ativo']),
        $produto->getDataDeCadastro(), // Mantém a data de cadastro original
        $_POST['dataDeValidade'] ?: null,
        $fornecedor // Passa o novo fornecedor
    );
    
    if ($produtoDAO->update($produtoAtualizado)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao atualizar.";
}
?>
<h1>Editar Produto</h1>
<form method="POST">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($produto->getNome()) ?>"><br>
    Preço: <input type="number" name="preco" step="0.01" value="<?= $produto->getPreco() ?>"><br>
    
    Fornecedor:
    <select name="fornecedor_id">
        <option value="">-- Sem Fornecedor --</option>
        <?php foreach ($fornecedores as $f): ?>
            <?php 
            // Verifica se o fornecedor do produto existe e se o ID bate com o da iteração atual
            $selected = ($produto->getFornecedor() && $produto->getFornecedor()->getId() == $f->getId()) ? 'selected' : ''; 
            ?>
            <option value="<?= $f->getId() ?>" <?= $selected ?>>
                <?= htmlspecialchars($f->getNome()) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    Ativo: <input type="checkbox" name="ativo" <?= $produto->getAtivo() ? 'checked' : '' ?>><br>
    Validade: <input type="date" name="dataDeValidade" value="<?= $produto->getDataDeValidade() ?>"><br>
    <button type="submit">Atualizar</button>
</form>
