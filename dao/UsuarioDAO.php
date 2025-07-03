<?php

require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../core/Database.php';

class UsuarioDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): Usuario
    {
        return new Usuario(
            $row['id'], $row['nome_completo'], $row['nome_usuario'], $row['senha'], $row['email'],
            $row['telefone'], $row['cpf'], (bool)$row['is_admin'], (bool)$row['ativo'], $row['token'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }
    
    public function create(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuario (nome_completo, nome_usuario, senha, email, telefone, cpf, is_admin, usuario_atualizacao) 
                VALUES (:nome_completo, :nome_usuario, :senha, :email, :telefone, :cpf, :is_admin, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(), // A senha já deve vir com hash
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => $usuario->isAdmin() ? 1 : 0,
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM usuario" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome_completo";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE usuario SET 
                    nome_completo = :nome_completo, nome_usuario = :nome_usuario, email = :email, 
                    telefone = :telefone, cpf = :cpf, is_admin = :is_admin, usuario_atualizacao = :user_id 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $usuario->getId(),
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => $usuario->isAdmin() ? 1 : 0,
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE usuario SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}