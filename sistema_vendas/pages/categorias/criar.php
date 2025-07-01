<?php
require_once '../core/Database.php';
require_once '../core/authService.php';
require_once '../dao/CategoriaDAO.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dao = new CategoriaDAO();
    if ($dao->create($_POST)) {
        header('Location: listar.php');
        exit();
    }
    $erro = "Erro ao criar categoria.";
}
require_once '../templates/header.php';
?>

<h1>Nova Categoria</h1>
<?php if (isset($erro)) echo "<p>$erro</p>"; ?>
<form method="POST">
    <div><label for="Nome">Nome:</label><input type="text" name="Nome" id="Nome" required></div>
    <div><label for="Descricao">Descrição:</label><textarea name="Descricao" id="Descricao"></textarea></div>
    <button type="submit">Salvar</button>
</form>
<?php require_once '../templates/footer.php'; ?>