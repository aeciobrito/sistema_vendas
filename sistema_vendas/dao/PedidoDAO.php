<?php

class PedidoDAO {

    private PDO $db;

    public function __construct() { $this->db = Database::getInstance(); }
    
    public function getAll() {
        $sql = "SELECT pe.*, cl.Nome as ClienteNome, fp.Nome as FormaPagamentoNome 
                FROM Pedido pe 
                JOIN Cliente cl ON pe.ClienteID = cl.Id 
                JOIN FormaPagamento fp ON pe.FormaPagamentoId = fp.Id 
                ORDER BY pe.DataPedido DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
   
    public function getById(int $id) {
        $sql = "SELECT pe.*, cl.Nome as ClienteNome, cl.Email, fp.Nome as FormaPagamentoNome 
                FROM Pedido pe 
                JOIN Cliente cl ON pe.ClienteID = cl.Id 
                JOIN FormaPagamento fp ON pe.FormaPagamentoId = fp.Id 
                WHERE pe.Id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function getItensByPedidoId(int $pedidoId) {
        $sql = "SELECT ip.*, pr.Nome as ProdutoNome, pr.Preco 
                FROM ItemPedido ip 
                JOIN Produto pr ON ip.ProdutoId = pr.Id 
                WHERE ip.PedidoId = :pedido_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pedido_id' => $pedidoId]);
        return $stmt->fetchAll();
    }
    
    public function create(array $data) {
        try {
            $this->db->beginTransaction();

            $sqlPedido = "INSERT INTO Pedido (ClienteID, DataPedido, FormaPagamentoId, Status, UsuarioAtualizacao) 
                          VALUES (:cliente_id, :data_pedido, :forma_pagamento_id, :status, :user_id)";
            $stmtPedido = $this->db->prepare($sqlPedido);
            $stmtPedido->execute([
                ':cliente_id' => $data['ClienteID'],
                ':data_pedido' => date('Y-m-d H:i:s'),
                ':forma_pagamento_id' => $data['FormaPagamentoId'],
                ':status' => 'Pendente',
                ':user_id' => getUserId()
            ]);
            $pedidoId = $this->db->lastInsertId();

            $sqlItem = "INSERT INTO ItemPedido (PedidoId, ProdutoId, Quantidade, UsuarioAtualizacao) 
                        VALUES (:pedido_id, :produto_id, :quantidade, :user_id)";
            $stmtItem = $this->db->prepare($sqlItem);

            foreach ($data['produtos'] as $produto) {
                $stmtItem->execute([
                    ':pedido_id' => $pedidoId,
                    ':produto_id' => $produto['id'],
                    ':quantidade' => $produto['qtd'],
                    ':user_id' => getUserId()
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}