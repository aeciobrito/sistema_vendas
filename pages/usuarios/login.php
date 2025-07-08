<?php
require_once __DIR__ . '/../../services/authService.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $erro = "Email ou senha inválidos!";
    } else {
        $dao = new UsuarioDAO();
        $usuario = $dao->getByEmail($email);

        if ($usuario && $usuario->isAtivo() && password_verify($senha, $usuario->getSenha())) {
            $token = bin2hex(random_bytes(32));
            $dao->updateToken($usuario->getId(), $token);
            
            $_SESSION['user_token'] = $token;
            
            // Redireciona para a página que o usuário tentou acessar ou para o index
            $redirect_url = $_SESSION['redirect_url'] ?? '/sistema_vendas/index.php';
            unset($_SESSION['redirect_url']); // Limpa a URL da sessão
            
            header('Location: ' . $redirect_url);
            exit();
        } else {
            $erro = "Email ou senha inválidos!";
        }
    }
}

require_once __DIR__ . '/../template/header.php';
?>

<h1>Login</h1>

<?php if ($erro): ?>
    <p style="color:red;"><?= htmlspecialchars($erro) ?></p>
<?php endif; ?>

<form action="login.php" method="POST">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>
<br>
<a href="cadastro.php">Ainda não tem conta? Cadastre-se</a>

<?php
require_once __DIR__ . '/../template/footer.php';
?>