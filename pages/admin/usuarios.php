<?php
require_once __DIR__ . '/../../services/authService.php';
requireAdmin(); // Proteção máxima: só admins podem ver esta página

require_once __DIR__ . '/../../dao/UsuarioDAO.php';

$usuarioDAO = new UsuarioDAO();
$usuarios = [];

// Lógica de busca
$searchTerm = $_GET['email'] ?? '';
if ($searchTerm) {
    $usuarioBuscado = $usuarioDAO->getByEmail($searchTerm);
    if ($usuarioBuscado) {
        $usuarios[] = $usuarioBuscado;
    }
} else {
    // Lista todos os usuários se não houver busca
    $usuarios = $usuarioDAO->getAll(false); // false para ver ativos e inativos
}

$loggedUser = getLoggedUser();

require_once __DIR__ . '/../template/header.php';
?>

<h1>Painel de Gestão de Usuários</h1>

<form method="GET" action="usuarios.php" class="form-group">
    <label for="email">Buscar Usuário por Email:</label>
    <input type="email" id="email" name="email" placeholder="Digite o email e pressione Enter" value="<?= htmlspecialchars($searchTerm) ?>">
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Status</th>
            <th>Admin?</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= $usuario->getId() ?></td>
            <td><?= htmlspecialchars($usuario->getNomeCompleto()) ?></td>
            <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
            <td><?= $usuario->isAtivo() ? 'Ativo' : 'Inativo' ?></td>
            <td><?= $usuario->isAdmin() ? 'Sim' : 'Não' ?></td>
            <td class="actions">
                <?php if ($usuario->getId() !== $loggedUser->getId()): // Impede auto-modificação ?>
                    <form action="acoes_usuario.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                        <input type="hidden" name="acao" value="toggle_admin">
                        <button type="submit" class="btn btn-secondary">
                            <?= $usuario->isAdmin() ? 'Remover Admin' : 'Tornar Admin' ?>
                        </button>
                    </form>
                    <form action="acoes_usuario.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                        <input type="hidden" name="acao" value="toggle_status">
                        <button type="submit" class="btn btn-secondary">
                            <?= $usuario->isAtivo() ? 'Desativar' : 'Ativar' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <span>(Usuário Atual)</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
require_once __DIR__ . '/../template/footer.php';
?>