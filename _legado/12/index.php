<?php
require_once __DIR__ . '/core/authService.php';
$user = getLoggedUser();
?>
<h1>Bem-vindo</h1>
<nav>
    <a href="./index.php">Home</a> |
    <a href="./produtos/listar.php">Produtos</a> |
    <a href="./fornecedores/listar.php">Fornecedores</a> |
    <?php if ($user): ?>
        <a href="./produtos/criar.php">Novo Produto</a> |
        <a href="./fornecedores/criar.php">Novo Fornecedor</a> |
        <a href="./logout.php">Sair (<?= htmlspecialchars($user->getNomeUsuario()) ?>)</a>
    <?php else: ?>
        <a href="./login.php">Login</a> |
        <a href="./cadastro.php">Cadastro</a>
    <?php endif; ?>
</nav>
