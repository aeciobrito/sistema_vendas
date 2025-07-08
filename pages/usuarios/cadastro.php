<?php
require_once __DIR__ . '/../../services/authService.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';
    $confirmSenha = $_POST['confirmSenha'] ?? '';

    if (empty($nome) || empty($email) || empty($senha) || $senha !== $confirmSenha) {
        $erro = "Dados inválidos ou senhas não conferem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {
        $dao = new UsuarioDAO();
        if ($dao->getByEmail($email)) {
            $erro = "Este email já está cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(32));
            
            // Adaptado para o construtor correto, usando email como nome de usuário
            $usuario = new Usuario(null, $nome, $email, $senhaHash, $email, null, null, false, true, $token);
            
            if ($dao->create($usuario, 1)) { // Supõe adminId=1 para criação
                $_SESSION['user_token'] = $token;
                header('Location: /sistema_vendas/index.php');
                exit();
            } else {
                $erro = "Ocorreu um erro ao realizar o cadastro. Tente novamente.";
            }
        }
    }
}

require_once __DIR__ . '/../template/header.php';
?>

<h1>Crie sua Conta</h1>

<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form action="cadastro.php" method="POST">
    <div class="form-group">
        <label for="nome">Nome Completo:</label>
        <input type="text" id="nome" name="nome" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
    </div>
    <div class="form-group">
        <label for="confirmSenha">Confirmar Senha:</label>
        <input type="password" id="confirmSenha" name="confirmSenha" required>
    </div>
    <button type="submit" class="btn btn-primary">Cadastrar</button>
</form>
<br>
<a href="login.php">Já tem uma conta? Faça o login</a>

<?php
require_once __DIR__ . '/../template/footer.php';
?>