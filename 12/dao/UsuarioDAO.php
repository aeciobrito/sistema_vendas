<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../model/Usuario.php';
 
class UsuarioDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch();

        if ($data) {
            return new Usuario($data['id'], $data['nomeUsuario'], $data['senha'], $data['email'], $data['token']);
        }
        return null;
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();

        if ($data) {
            return new Usuario($data['id'], $data['nomeUsuario'], $data['senha'], $data['email'], $data['token']);
        }
        return null;
    }

    public function getByToken(string $token): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE token = :token");
        $stmt->execute([':token' => $token]);
        $data = $stmt->fetch();

        if ($data) {
            return new Usuario($data['id'], $data['nomeUsuario'], $data['senha'], $data['email'], $data['token']);
        }
        return null;
    }

    public function create(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuario (nomeUsuario, senha, email, token) VALUES (:nome, :senha, :email, :token)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(),
            ':email' => $usuario->getEmail(),
            ':token' => $usuario->getToken()
        ]);
    }

    public function updateToken(int $id, ?string $token): bool
    {
        $sql = "UPDATE usuario SET token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':token' => $token,
            ':id' => $id
        ]);
    }
}
