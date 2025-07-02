<?php
require_once __DIR__ . '/../dao/FornecedorDAO.php';
$dao = new FornecedorDAO();
$fornecedores = $dao->getAll();
?>
<h1>Fornecedores</h1>
<a href="criar.php">Novo Fornecedor</a>
<hr>
<?php if (empty($fornecedores)): ?>
    <p>Nenhum fornecedor cadastrado.</p>
<?php else: ?>
    <?php foreach ($fornecedores as $f): ?>
        <p>
            <b><?= htmlspecialchars($f->getNome()) ?></b><br>
            CNPJ: <?= htmlspecialchars($f->getCnpj() ?: 'Não informado') ?><br>
            Contato: <?= htmlspecialchars($f->getContato() ?: 'Não informado') ?>
        </p>
    <?php endforeach; ?>
<?php endif; ?>
<br>
<a href="../index.php">Voltar para Home</a>
