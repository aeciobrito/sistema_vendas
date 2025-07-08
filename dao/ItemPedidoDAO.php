<?php

require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../database/Database.php';

class ItemPedidoDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Este mapObject não precisa de auditoria, mas precisa buscar o Produto.
    private function mapObject(array $row): ItemPedido
    {
        $produto = null;
        if (!empty($row['produto_id'])) {
            $produtoDao = new ProdutoDAO();
            $produto = $produtoDao->getById($row['produto_id']);
        }

        // Se o produto não for encontrado (ex: foi deletado), pode ser um problema.
        // O construtor espera um objeto Produto, não um nulo. 
        // Aqui, lançamos uma exceção se o produto associado não existir mais.
        if (!$produto) {
            throw new Exception("Produto com ID {$row['produto_id']} não encontrado para o item de pedido {$row['id']}.");
        }

        return new ItemPedido(
            $row['id'],
            $row['pedido_id'],
            $produto,
            (int)$row['quantidade'],
            (float)$row['preco_unitario']
        );
    }

    // Não há um 'create' isolado, pois itens só existem dentro de um pedido.
    // A criação é feita pelo PedidoDAO. No entanto, um método para uso interno é útil.
    public function create(ItemPedido $item, int $pedidoId): bool
    {
        $sql = "INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco_unitario) 
                VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pedido_id' => $pedidoId,
            ':produto_id' => $item->getProduto()->getId(),
            ':quantidade' => $item->getQuantidade(),
            ':preco_unitario' => $item->getPrecoUnitario()
        ]);
    }
    
    // O método mais comum para itens de pedido é buscar todos de um pedido específico.
    public function getByPedidoId(int $pedidoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM item_pedido WHERE pedido_id = :pedido_id");
        $stmt->execute([':pedido_id' => $pedidoId]);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        return $result;
    }
    
    // Deletar todos os itens de um pedido, útil para o método `update` do PedidoDAO
    public function deleteByPedidoId(int $pedidoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM item_pedido WHERE pedido_id = :pedido_id");
        return $stmt->execute([':pedido_id' => $pedidoId]);
    }
}