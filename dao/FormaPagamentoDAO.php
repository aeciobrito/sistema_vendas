<?php

require_once __DIR__ . '/../model/FormaPagamento.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../database/Database.php';

class FormaPagamentoDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): FormaPagamento
    {
        $usuarioAtualizacao = null;
        if (!empty($row['usuario_atualizacao'])) {
            $usuarioDao = new UsuarioDAO(); // Dependência de outro DAO
            $usuarioAtualizacao = $usuarioDao->getById($row['usuario_atualizacao']);
        }

        return new FormaPagamento(
            $row['id'],
            $row['nome'],
            $row['descricao'],
            (bool)$row['ativo'],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao
        );
    }

    public function create(FormaPagamento $formaPagamento, int $usuarioId): bool
    {
        $sql = "INSERT INTO forma_pagamento (nome, descricao, usuario_atualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => $usuarioId
        ]);
    }

    public function getById(int $id): ?FormaPagamento
    {
        $stmt = $this->db->prepare("SELECT * FROM forma_pagamento WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM forma_pagamento" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        return $result;
    }

    public function update(FormaPagamento $formaPagamento, int $usuarioId): bool
    {
        $sql = "UPDATE forma_pagamento SET nome = :nome, descricao = :descricao, usuario_atualizacao = :user_id, ativo = :ativo WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $formaPagamento->getId(),
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => $usuarioId,
            ':ativo' => (int)$formaPagamento->isAtivo()
        ]);
    }

    public function softDelete(int $id, int $usuarioId): bool
    {
        $sql = "UPDATE forma_pagamento SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $usuarioId]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM forma_pagamento WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}