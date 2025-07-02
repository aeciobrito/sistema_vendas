<?php

class UsuarioDAO extends BaseDAO
{
    private function mapObject(array $row): Usuario
    {
        $grupoDAO = new GrupoUsuarioDAO();
        $grupo = isset($row['GrupoUsuarioID']) ? $grupoDAO->getById($row['GrupoUsuarioID']) : null;

        return new Usuario(
            $row['Id'], $row['NomeUsuario'], $row['Senha'], $row['Email'], $grupo, (bool)$row['Ativo'],
            $row['Token'], $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }
    
    public function create(Usuario $usuario): bool
    {
        $sql = "INSERT INTO Usuario (NomeUsuario, Senha, Email, GrupoUsuarioID, Token, UsuarioAtualizacao) 
                VALUES (:nome_usuario, :senha, :email, :grupo_id, :token, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(),
            ':email' => $usuario->getEmail(),
            ':grupo_id' => $usuario->getGrupoUsuario() ? $usuario->getGrupoUsuario()->getId() : null,
            ':token' => $usuario->getToken(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Usuario" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY NomeUsuario";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE Usuario SET NomeUsuario = :nome_usuario, Email = :email, GrupoUsuarioID = :grupo_id, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $usuario->getId(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':email' => $usuario->getEmail(),
            ':grupo_id' => $usuario->getGrupoUsuario() ? $usuario->getGrupoUsuario()->getId() : null,
            ':user_id' => 1
        ]);
    }

    public function updatePassword(int $id, string $novaSenhaHash): bool
    {
        $sql = "UPDATE Usuario SET Senha = :senha, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':senha' => $novaSenhaHash, ':user_id' => 1]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Usuario SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Usuario WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}