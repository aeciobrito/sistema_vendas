<?php

class PedidoDAO extends BaseDAO
{
    private function mapObject(array $row): Pedido
    {
        $clienteDAO = new ClienteDAO();
        $formaPagamentoDAO = new FormaPagamentoDAO();
        $itemPedidoDAO = new ItemPedidoDAO();

        $cliente = $clienteDAO->getById($row['ClienteID']);
        $formaPagamento = $formaPagamentoDAO->getById($row['FormaPagamentoId']);
        $itens = $itemPedidoDAO->getByPedidoId($row['Id']);

        return new Pedido(
            $row['Id'], $cliente, $row['DataPedido'], $formaPagamento, $row['Status'], (bool)$row['Ativo'], $itens,
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }
    
    public function create(Pedido $pedido): ?int
    {
        try {
            $this->db->beginTransaction();

            $sqlPedido = "INSERT INTO Pedido (ClienteID, DataPedido, FormaPagamentoId, Status, UsuarioAtualizacao) 
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
                // É preciso criar um novo objeto ItemPedido com o PedidoId correto
                $itemParaSalvar = new ItemPedido(
                    null, $pedidoId, $item->getProduto(), $item->getQuantidade(), $item->getPrecoUnitario()
                );
                $itemPedidoDAO->create($itemParaSalvar);
            }

            $this->db->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage()); // Logar o erro é uma boa prática
            return null;
        }
    }

    public function getById(int $id): ?Pedido
    {
        $stmt = $this->db->prepare("SELECT * FROM Pedido WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM Pedido" . ($somenteAtivos ? " WHERE Ativo = 1" : "") . " ORDER BY DataPedido DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE Pedido SET Status = :status, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status, ':user_id' => 1]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE Pedido SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        // ON DELETE CASCADE no banco de dados cuidará dos Itens do Pedido.
        $stmt = $this->db->prepare("DELETE FROM Pedido WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}