<?php

class PermissaoDAO extends BaseDAO
{
    private function mapObject(array $row): Permissao
    {
        return new Permissao(
            $row['Id'], $row['Nome'], $row['Descricao'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(Permissao $permissao): bool
    {
        $sql = "INSERT INTO Permissao (Nome, Descricao, UsuarioAtualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $permissao->getNome(),
            ':descricao' => $permissao->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Permissao
    {
        $stmt = $this->db->prepare("SELECT * FROM Permissao WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Permissao" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Permissao $permissao): bool
    {
        $sql = "UPDATE Permissao SET Nome = :nome, Descricao = :descricao, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $permissao->getId(),
            ':nome' => $permissao->getNome(),
            ':descricao' => $permissao->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Permissao SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Permissao WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}