<?php

class ClienteDAO extends BaseDAO
{
    private function mapObject(array $row): Cliente
    {
        return new Cliente(
            $row['Id'], $row['Nome'], $row['Email'], $row['Telefone'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(Cliente $cliente): bool
    {
        $sql = "INSERT INTO Cliente (Nome, Email, Telefone, UsuarioAtualizacao) VALUES (:nome, :email, :telefone, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $cliente->getNome(),
            ':email' => $cliente->getEmail(),
            ':telefone' => $cliente->getTelefone(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Cliente
    {
        $stmt = $this->db->prepare("SELECT * FROM Cliente WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Cliente" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Cliente $cliente): bool
    {
        $sql = "UPDATE Cliente SET Nome = :nome, Email = :email, Telefone = :telefone, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $cliente->getId(),
            ':nome' => $cliente->getNome(),
            ':email' => $cliente->getEmail(),
            ':telefone' => $cliente->getTelefone(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Cliente SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Cliente WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}