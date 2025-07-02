<?php

class ProdutoDAO extends BaseDAO
{
    private function mapObject(array $row): Produto
    {
        $categoriaDAO = new CategoriaDAO();
        $categoria = isset($row['CategoriaID']) ? $categoriaDAO->getById($row['CategoriaID']) : null;

        return new Produto(
            $row['Id'], $row['Nome'], $row['Descricao'], (float)$row['Preco'], $categoria, (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(Produto $produto): bool
    {
        $sql = "INSERT INTO Produto (Nome, Descricao, Preco, CategoriaID, UsuarioAtualizacao) 
                VALUES (:nome, :descricao, :preco, :categoria_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $produto->getCategoria() ? $produto->getCategoria()->getId() : null,
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Produto
    {
        $stmt = $this->db->prepare("SELECT * FROM Produto WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Produto" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY Nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Produto $produto): bool
    {
        $sql = "UPDATE Produto SET Nome = :nome, Descricao = :descricao, Preco = :preco, CategoriaID = :categoria_id, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $produto->getId(),
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $produto->getCategoria() ? $produto->getCategoria()->getId() : null,
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Produto SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM Produto WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}