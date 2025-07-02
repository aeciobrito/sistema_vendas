<?php
class CategoriaDAO {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM Categoria WHERE Ativo = 1 ORDER BY Nome");
        return $stmt->fetchAll();
    }
    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM Categoria WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    public function create(array $data) {
        $sql = "INSERT INTO Categoria (Nome, Descricao, UsuarioAtualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['Nome'],
            ':descricao' => $data['Descricao'],
            ':user_id' => getUserId()
        ]);
    }
}