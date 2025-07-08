<?php
require_once("../backend/config/Database.php");
require_once("../backend/entity/Produto.php");
require_once("../backend/dao/ProdutoDAO.php");

$produtoDAO = new ProdutoDAO();

if (isset($_GET['id'])) {
    $produtoId = $_GET['id'];
    $produto = $produtoDAO->getById($produtoId);

    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }
} else {
    echo "ID do produto não fornecido.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] === 'editar') {

            $produto->setNome($_POST['nome']);
            $produto->setDescricao($_POST['descricao']);
            $produto->setPreco($_POST['preco']);
            $produto->setCategoriaID($_POST['categoriaID']);
            $produto->setUsuarioAtualizacao(null);
            $produto->setAtivo(isset($_POST['ativo']) ? 1 : 0);

            $produtoDAO->update($produto); // Atualiza o produto no banco
            header("Location: produtoDetalhes.php?id=$produtoId");
            exit;
        } elseif ($_POST['acao'] === 'excluir') {
            $produtoDAO->delete($produtoId);
            header("Location: home.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Produto</title>
</head>
<body>
    <h1>Detalhes do Produto</h1>
    <form method="post">
        <input type="hidden" name="acao" value="editar">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto->getNome(), ENT_QUOTES, 'UTF-8'); ?>">
        <br>
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($produto->getDescricao(), ENT_QUOTES, 'UTF-8'); ?></textarea>
        <br>
        <label for="preco">Preço:</label>
        <input type="text" id="preco" name="preco" value="<?php echo htmlspecialchars($produto->getPreco(), ENT_QUOTES, 'UTF-8'); ?>">
        <br>
        <label for="categoriaID">Categoria ID:</label>
        <input type="text" id="categoriaID" name="categoriaID" value="<?php echo htmlspecialchars($produto->getCategoriaID(), ENT_QUOTES, 'UTF-8'); ?>">
        <br>
        <label for="ativo">Ativo:</label>
        <input type="checkbox" id="ativo" name="ativo" <?php echo $produto->getAtivo() ? 'checked' : ''; ?>>
        <br>
        <button type="submit">Salvar</button>
    </form>
    <form method="post">
        <input type="hidden" name="acao" value="excluir">
        <button type="submit">Excluir</button>
    </form>
    <a href="home.php">Voltar</a>
</body>
</html>
