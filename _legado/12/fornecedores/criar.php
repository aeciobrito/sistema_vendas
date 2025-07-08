<?php
require_once __DIR__ . '/../core/authService.php';
requireLogin(); // Apenas usuários logados podem criar
require_once __DIR__ . '/../dao/FornecedorDAO.php';
require_once __DIR__ . '/../model/Fornecedor.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cnpj = $_POST['cnpj'] ?? null;
    $contato = $_POST['contato'] ?? null;

    $fornecedor = new Fornecedor(null, $nome, $cnpj, $contato);
    $dao = new FornecedorDAO();
    if ($dao->create($fornecedor)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao criar fornecedor.";
}
?>
<h1>Criar Fornecedor</h1>
<?php if (isset($erro)) echo "<p style='color:red'>$erro</p>"; ?>
<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    CNPJ: <input type="text" name="cnpj"><br>
    Contato: <input type="text" name="contato"><br>
    <button type="submit">Salvar</button>
</form>
<br>
<a href="listar.php">Voltar para a lista</a>
