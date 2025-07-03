<?php

require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../core/Database.php';

class ItemPedidoDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): ItemPedido
    {
        $produtoDAO = new ProdutoDAO();
        $produto = $produtoDAO->getById($row['produto_id']);

        return new ItemPedido(
            $row['id'], $row['pedido_id'], $produto, (int)$row['quantidade'], (float)$row['preco_unitario']
        );
    }

    public function create(ItemPedido $item): bool
    {
        $sql = "INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco_unitario, usuario_atualizacao) 
                VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pedido_id' => $item->getPedidoId(),
            ':produto_id' => $item->getProduto()->getId(),
            ':quantidade' => $item->getQuantidade(),
            ':preco_unitario' => $item->getPrecoUnitario(),
            ':user_id' => 1
        ]);
    }
    
    public function getByPedidoId(int $pedidoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM item_pedido WHERE pedido_id = :pedido_id");
        $stmt->execute([':pedido_id' => $pedidoId]);
        $itens = [];
        foreach ($stmt->fetchAll() as $row) {
            $itens[] = $this->mapObject($row);
        }
        return $itens;
    }
}