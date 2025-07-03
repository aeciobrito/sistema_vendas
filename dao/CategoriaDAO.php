<?php

class CategoriaDAO extends BaseDAO
{
    private function mapObject(array $row): Categoria
    {
        return new Categoria(
            $row['id'], $row['nome'], $row['descricao'], (bool)$row['ativo'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }

    public function create(Categoria $categoria): bool
    {
        $sql = "INSERT INTO categoria (nome, descricao, usuario_atualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => 1 // Placeholder
        ]);
    }

    public function getById(int $id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT * FROM categoria WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM categoria" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Categoria $categoria): bool
    {
        $sql = "UPDATE categoria SET nome = :nome, descricao = :descricao, usuario_atualizacao = :user_id WHERE id = :id";
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
        $sql = "UPDATE categoria SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}