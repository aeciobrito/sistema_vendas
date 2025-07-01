<?php

class FormaPagamentoDAO {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM FormaPagamento WHERE Ativo = 1 ORDER BY Nome");
        return $stmt->fetchAll();
    }
}
