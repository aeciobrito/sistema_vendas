<?php
require_once __DIR__ . '/../model/Fornecedor.php';
require_once __DIR__ . '/../core/Database.php';

class FornecedorDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getById(int $id): ?Fornecedor
    {
        $stmt = $this->db->prepare("SELECT * FROM fornecedores WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();
        if ($data) {
            return new Fornecedor($data['id'], $data['nome'], $data['cnpj'], $data['contato']);
        }
        return null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM fornecedores ORDER BY nome ASC");
        $data = $stmt->fetchAll();
        $fornecedores = [];
        foreach ($data as $row) {
            $fornecedores[] = new Fornecedor($row['id'], $row['nome'], $row['cnpj'], $row['contato']);
        }
        return $fornecedores;
    }

    public function create(Fornecedor $fornecedor): bool
    {
        $sql = "INSERT INTO fornecedores (nome, cnpj, contato) VALUES (:nome, :cnpj, :contato)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nome' => $fornecedor->getNome(),
            ':cnpj' => $fornecedor->getCnpj(),
            ':contato' => $fornecedor->getContato()
        ]);
    }
}
