<?php

require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../database/Database.php';

class UsuarioDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): Usuario
    {
        // Para o auto-relacionamento, buscamos o usuário que atualizou, se houver.
        // Cuidado com loops infinitos se não for tratado corretamente. A base é a existência do ID.
        $usuarioAtualizacao = null;
        if (!empty($row['usuario_atualizacao'])) {
             // Evita que o objeto tente buscar a si mesmo infinitamente se for o primeiro usuário
            if ($row['id'] != $row['usuario_atualizacao']) {
                 $usuarioAtualizacao = $this->getById($row['usuario_atualizacao']);
            }
        }

        return new Usuario(
            $row['id'],
            $row['nome_completo'],
            $row['nome_usuario'],
            $row['senha'],
            $row['email'],
            $row['telefone'],
            $row['cpf'],
            (bool)$row['is_admin'],
            (bool)$row['ativo'],
            $row['token'],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao
        );
    }

    public function create(Usuario $usuario, int $adminId): bool
    {
        $sql = "INSERT INTO usuario (nome_completo, nome_usuario, senha, email, telefone, cpf, is_admin, token, usuario_atualizacao) 
                VALUES (:nome_completo, :nome_usuario, :senha, :email, :telefone, :cpf, :is_admin, :token, :user_id)";
        
        $stmt = $this->db->prepare($sql);
        
        // A senha deve ser armazenada como hash, nunca como texto puro.
        // Ex: password_hash($usuario->getSenha(), PASSWORD_DEFAULT)
        return $stmt->execute([
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(), // Lembre-se de usar hash aqui!
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => (int)$usuario->isAdmin(),
            ':token' => $usuario->getToken(),
            ':user_id' => $adminId
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getByUsername(string $username): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE nome_usuario = :username");
        $stmt->execute([':username' => $username]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM usuario" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome_completo";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        return $result;
    }
    
    public function update(Usuario $usuario, int $adminId): bool
    {
        $sql = "UPDATE usuario SET 
                    nome_completo = :nome_completo, 
                    nome_usuario = :nome_usuario, 
                    email = :email, 
                    telefone = :telefone, 
                    cpf = :cpf, 
                    is_admin = :is_admin,
                    ativo = :ativo,
                    usuario_atualizacao = :user_id 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $usuario->getId(),
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => (int)$usuario->isAdmin(), // Campo agora atualizável
            ':ativo' => (int)$usuario->isAtivo(),     // Campo agora atualizável
            ':user_id' => $adminId
        ]);
    }

    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getByToken(string $token): ?Usuario
    {
        // Busca por token apenas se o usuário estiver ativo
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE token = :token AND ativo = 1 LIMIT 1");
        $stmt->execute([':token' => $token]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function updateToken(int $id, string $token): bool
    {
        $sql = "UPDATE usuario SET token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':token' => $token, ':id' => $id]);
    }

    public function softDelete(int $id, int $adminId): bool
    {
        $sql = "UPDATE usuario SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $adminId]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}