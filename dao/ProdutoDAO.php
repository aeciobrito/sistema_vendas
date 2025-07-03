<?php

class ProdutoDAO extends BaseDAO
{
    private function mapObject(array $row): Produto
    {
        $categoriaDAO = new CategoriaDAO();
        $categoria = isset($row['categoria_id']) ? $categoriaDAO->getById($row['categoria_id']) : null;

        return new Produto(
            $row['id'], $row['nome'], $row['descricao'], (float)$row['preco'], $categoria, (bool)$row['ativo'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }

    public function create(Produto $produto): bool
    {
        $sql = "INSERT INTO produto (nome, descricao, preco, categoria_id, usuario_atualizacao) 
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
        $stmt = $this->db->prepare("SELECT * FROM produto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM produto" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Produto $produto): bool
    {
        $sql = "UPDATE produto SET nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id, usuario_atualizacao = :user_id WHERE id = :id";
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
        $sql = "UPDATE produto SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produto WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}