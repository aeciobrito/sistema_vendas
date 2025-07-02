<?php

class FormaPagamentoDAO extends BaseDAO
{
    private function mapObject(array $row): FormaPagamento
    {
        return new FormaPagamento(
            $row['Id'], $row['Nome'], $row['Descricao'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(FormaPagamento $formaPagamento): bool
    {
        $sql = "INSERT INTO FormaPagamento (Nome, Descricao, UsuarioAtualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?FormaPagamento
    {
        $stmt = $this->db->prepare("SELECT * FROM FormaPagamento WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM FormaPagamento" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(FormaPagamento $formaPagamento): bool
    {
        $sql = "UPDATE FormaPagamento SET Nome = :nome, Descricao = :descricao, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $formaPagamento->getId(),
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE FormaPagamento SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM FormaPagamento WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}