<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin();
// Este arquivo apenas exibe o formulário. O processamento é feito em 'acoes.php'
require_once __DIR__ . '/../template/header.php';
?>

<h1>Nova Categoria</h1>

<form action="acoes.php" method="POST">
    <input type="hidden" name="acao" value="criar">
    <div class="form-group">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php
require_once __DIR__ . '/../template/footer.php';
?>