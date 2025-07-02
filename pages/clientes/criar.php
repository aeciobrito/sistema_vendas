<?php
require_once '../core/Database.php';
require_once '../core/authService.php';
require_once '../dao/ClienteDAO.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new ClienteDAO();
    if ($dao->create($_POST)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao criar cliente.";
}
require_once '../templates/header.php';
?>
<h1>Novo Cliente</h1>
<?php if (isset($erro)) echo "<p>$erro</p>"; ?>
<form method="POST">
    <div><label for="Nome">Nome:</label><input type="text" name="Nome" id="Nome" required></div>
    <div><label for="Email">Email:</label><input type="email" name="Email" id="Email"></div>
    <div><label for="Telefone">Telefone:</label><input type="text" name="Telefone" id="Telefone"></div>
    <button type="submit">Salvar</button>
</form>
<?php require_once '../templates/footer.php'; ?>