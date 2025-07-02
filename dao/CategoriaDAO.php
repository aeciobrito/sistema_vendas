<?php

class CategoriaDAO extends BaseDAO
{
    private function mapObject(array $row): Categoria
    {
        return new Categoria(
            $row['Id'], $row['Nome'], $row['Descricao'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(Categoria $categoria): bool
    {
        $sql = "INSERT INTO Categoria (Nome, Descricao, UsuarioAtualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => 1 // Placeholder
        ]);
    }

    public function getById(int $id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT * FROM Categoria WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Categoria" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Categoria $categoria): bool
    {
        $sql = "UPDATE Categoria SET Nome = :nome, Descricao = :descricao, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $categoria->getId(),
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => 1 // Placeholder
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Categoria SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Categoria WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}