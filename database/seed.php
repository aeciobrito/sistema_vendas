<?php
// Este script deve ser executado uma única vez para inicializar o sistema.

header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/../bootstrap.php';

echo "Iniciando script de inicialização (seeder)...\n";

try {
    $usuarioDAO = new UsuarioDAO();

    // 1. Verifica se o admin já existe
    $adminEmail = 'admin@sistema.com';
    if ($usuarioDAO->getByEmail($adminEmail)) {
        echo "O usuário administrador já existe. Nenhuma ação foi tomada.\n";
        exit;
    }

    // 2. Define os dados do administrador padrão
    $nome = 'Administrador do Sistema';
    $nomeUsuario = 'admin'; // Nome de usuário para login, se aplicável
    $senha = 'Root@123';
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    // 3. Cria o objeto Usuario com permissão de admin (isAdmin = true)
    $adminUser = new Usuario(
        null,
        $nome,
        $nomeUsuario,
        $senhaHash,
        $adminEmail,
        null, // telefone
        null, // cpf
        true, // isAdmin
        true, // ativo
        $token
    );

    // 4. Salva o usuário no banco. O ID 1 (sistema) é o criador do primeiro usuário.
    if ($usuarioDAO->create($adminUser, 1)) {
        echo "Usuário administrador criado com sucesso!\n";
        echo "Email: " . $adminEmail . "\n";
        echo "Senha: " . $senha . "\n";
        echo "Por favor, altere esta senha após o primeiro login por segurança.\n";
    } else {
        echo "Falha ao criar o usuário administrador.\n";
    }

} catch (Exception $e) {
    echo "Ocorreu um erro: " . $e->getMessage() . "\n";
}

echo "Script de inicialização concluído.\n";