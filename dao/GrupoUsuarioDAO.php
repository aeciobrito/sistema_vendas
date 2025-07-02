<?php

class GrupoUsuarioDAO extends BaseDAO
{
    private function mapObject(array $row): GrupoUsuario
    {
        return new GrupoUsuario(
            $row['Id'], $row['Nome'], $row['Descricao'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(GrupoUsuario $grupo): bool
    {
        $sql = "INSERT INTO GrupoUsuario (Nome, Descricao, UsuarioAtualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $grupo->getNome(),
            ':descricao' => $grupo->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?GrupoUsuario
    {
        $stmt = $this->db->prepare("SELECT * FROM GrupoUsuario WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }
    
    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM GrupoUsuario" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(GrupoUsuario $grupo): bool
    {
        $sql = "UPDATE GrupoUsuario SET Nome = :nome, Descricao = :descricao, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $grupo->getId(),
            ':nome' => $grupo->getNome(),
            ':descricao' => $grupo->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE GrupoUsuario SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM GrupoUsuario WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}