<?php

class PedidoDAO extends BaseDAO
{
    private function mapObject(array $row): Pedido
    {
        $usuarioDAO = new UsuarioDAO();
        $formaPagamentoDAO = new FormaPagamentoDAO();
        $itemPedidoDAO = new ItemPedidoDAO();

        $cliente = $usuarioDAO->getById($row['cliente_id']);
        $formaPagamento = $formaPagamentoDAO->getById($row['forma_pagamento_id']);
        $itens = $itemPedidoDAO->getByPedidoId($row['id']);

        return new Pedido(
            $row['id'], $cliente, $row['data_pedido'], $formaPagamento, $row['status'], (bool)$row['ativo'], $itens,
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }
    
    public function create(Pedido $pedido): ?int
    {
        try {
            $this->db->beginTransaction();

            $sqlPedido = "INSERT INTO pedido (cliente_id, data_pedido, forma_pagamento_id, status, usuario_atualizacao) 
                          VALUES (:cliente_id, :data_pedido, :forma_pagamento_id, :status, :user_id)";
            $stmtPedido = $this->db->prepare($sqlPedido);
            $stmtPedido->execute([
                ':cliente_id' => $pedido->getCliente()->getId(),
                ':data_pedido' => $pedido->getDataPedido(),
                ':forma_pagamento_id' => $pedido->getFormaPagamento()->getId(),
                ':status' => $pedido->getStatus(),
                ':user_id' => 1
            ]);
            $pedidoId = (int)$this->db->lastInsertId();

            $itemPedidoDAO = new ItemPedidoDAO();
            foreach ($pedido->getItens() as $item) {
                $itemParaSalvar = new ItemPedido(
                    null, $pedidoId, $item->getProduto(), $item->getQuantidade(), $item->getPrecoUnitario()
                );
                $itemPedidoDAO->create($itemParaSalvar);
            }

            $this->db->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return null;
        }
    }

    public function getById(int $id): ?Pedido
    {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM pedido" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY data_pedido DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE pedido SET status = :status, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status, ':user_id' => 1]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE pedido SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pedido WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}