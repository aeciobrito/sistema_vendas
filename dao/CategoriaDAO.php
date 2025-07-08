<?php

require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/UsuarioDAO.php';
require_once __DIR__ . '/../database/Database.php';

class CategoriaDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): Categoria
    {
        $usuarioAtualizacao = null;
        if (!empty($row['usuario_atualizacao'])) {
            $usuarioDao = new UsuarioDAO(); 
            $usuarioAtualizacao = $usuarioDao->getById($row['usuario_atualizacao']);
        }

        return new Categoria(
            (int)$row['id'],
            $row['nome'],
            $row['descricao'],
            (bool)$row['ativo'],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao // Agora é um objeto Usuario ou null, como a model espera
        );
    }

    public function create(Categoria $categoria, int $usuarioId): bool
    {
        $sql = "INSERT INTO categoria (nome, descricao, usuario_atualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => $usuarioId
        ]);
    }

    public function getById(int $id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT * FROM categoria WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM categoria";
        if ($somenteAtivos) {
            $sql .= " WHERE ativo = 1";
        }
        $sql .= " ORDER BY nome";

        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        
        return $result;
    }

    public function update(Categoria $categoria, int $usuarioId): bool
    {
        $sql = "UPDATE categoria SET 
                    nome = :nome, 
                    descricao = :descricao, 
                    ativo = :ativo,
                    usuario_atualizacao = :user_id 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $categoria->getId(),
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':ativo' => (int)$categoria->isAtivo(),
            ':user_id' => $usuarioId
        ]);
    }

    public function softDelete(int $id, int $usuarioId): bool
    {
        $sql = "UPDATE categoria SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id, 
            ':user_id' => $usuarioId
        ]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}