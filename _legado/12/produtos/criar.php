<?php
require_once __DIR__ . '/../core/authService.php';
requireLogin();
require_once __DIR__ . '/../dao/ProdutoDAO.php';
require_once __DIR__ . '/../dao/FornecedorDAO.php'; // Precisa do DAO de fornecedor
require_once __DIR__ . '/../model/Produto.php';

// Busca todos os fornecedores para popular o <select>
$fornecedorDAO = new FornecedorDAO();
$fornecedores = $fornecedorDAO->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = (float) ($_POST['preco'] ?? 0);
    $ativo = isset($_POST['ativo']);
    $validade = $_POST['dataDeValidade'] ?: null;
    $cadastro = date('Y-m-d');
    
    $fornecedorId = filter_input(INPUT_POST, 'fornecedor_id', FILTER_VALIDATE_INT);
    // Busca o objeto fornecedor completo com base no ID recebido do form
    $fornecedor = $fornecedorId ? $fornecedorDAO->getById($fornecedorId) : null;

    // Cria o produto já com o objeto Fornecedor
    $produto = new Produto(null, $nome, $preco, $ativo, $cadastro, $validade, $fornecedor);
    
    $produtoDAO = new ProdutoDAO();
    if ($produtoDAO->create($produto)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao criar produto.";
}
?>
<h1>Criar Produto</h1>
<?php if (isset($erro)) echo "<p style='color:red'>$erro</p>"; ?>
<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Preço: <input type="number" name="preco" step="0.01" required><br>
    
    Fornecedor: 
    <select name="fornecedor_id">
        <option value="">-- Sem Fornecedor --</option>
        <?php foreach ($fornecedores as $f): ?>
            <option value="<?= $f->getId() ?>">
                <?= htmlspecialchars($f->getNome()) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    Ativo: <input type="checkbox" name="ativo" checked><br>
    Validade: <input type="date" name="dataDeValidade"><br>
    <button type="submit">Salvar</button>
</form>
