<?php

class ClienteDAO {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM Cliente WHERE Ativo = 1 ORDER BY Nome");
        return $stmt->fetchAll();
    }
    public function create(array $data) {
        $sql = "INSERT INTO Cliente (Nome, Email, Telefone, UsuarioAtualizacao) VALUES (:nome, :email, :telefone, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $data['Nome'],
            ':email' => $data['Email'],
            ':telefone' => $data['Telefone'],
            ':user_id' => getUserId()
        ]);
    }
}