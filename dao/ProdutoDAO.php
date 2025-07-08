<?php

require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../database/Database.php';

class ProdutoDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): Produto
    {
        $usuarioAtualizacao = null;
        if (!empty($row['usuario_atualizacao'])) {
            $usuarioDao = new UsuarioDAO();
            $usuarioAtualizacao = $usuarioDao->getById($row['usuario_atualizacao']);
        }
        
        $categoria = null;
        if (!empty($row['categoria_id'])) {
            // Supondo que você tenha o CategoriaDAO pronto.
            $categoriaDao = new CategoriaDAO(); 
            $categoria = $categoriaDao->getById($row['categoria_id']);
        }

        return new Produto(
            $row['id'],
            $row['nome'],
            $row['descricao'],
            (float)$row['preco'],
            $categoria,
            (bool)$row['ativo'],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao
        );
    }

    public function create(Produto $produto, int $usuarioId): bool
    {
        $sql = "INSERT INTO produto (nome, descricao, preco, categoria_id, usuario_atualizacao) 
                VALUES (:nome, :descricao, :preco, :categoria_id, :user_id)";
        $stmt = $this->db->prepare($sql);

        $categoriaId = $produto->getCategoria() ? $produto->getCategoria()->getId() : null;

        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $categoriaId,
            ':user_id' => $usuarioId
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
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        return $result;
    }

    public function update(Produto $produto, int $usuarioId): bool
    {
        $sql = "UPDATE produto SET 
                    nome = :nome, 
                    descricao = :descricao, 
                    preco = :preco, 
                    categoria_id = :categoria_id, 
                    ativo = :ativo,
                    usuario_atualizacao = :user_id 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $categoriaId = $produto->getCategoria() ? $produto->getCategoria()->getId() : null;

        return $stmt->execute([
            ':id' => $produto->getId(),
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $categoriaId,
            ':ativo' => (int)$produto->isAtivo(),
            ':user_id' => $usuarioId
        ]);
    }

    public function softDelete(int $id, int $usuarioId): bool
    {
        $sql = "UPDATE produto SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $usuarioId]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produto WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}