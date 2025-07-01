<?php

class ProdutoDAO {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    public function getAll() {
        $sql = "SELECT p.*, c.Nome as CategoriaNome FROM Produto p LEFT JOIN Categoria c ON p.CategoriaID = c.Id WHERE p.Ativo = 1 ORDER BY p.Nome";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    public function getById(int $id) {
        $sql = "SELECT p.*, c.Nome as CategoriaNome FROM Produto p LEFT JOIN Categoria c ON p.CategoriaID = c.Id WHERE p.Id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function create(array $data) {
        $sql = "INSERT INTO Produto (Nome, Descricao, Preco, CategoriaID, UsuarioAtualizacao) VALUES (:nome, :descricao, :preco, :categoria_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['Nome'],
            ':descricao' => $data['Descricao'],
            ':preco' => $data['Preco'],
            ':categoria_id' => $data['CategoriaID'],
            ':user_id' => getUserId()
        ]);
    }
}