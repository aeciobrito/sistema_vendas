<?php

class ItemPedidoDAO extends BaseDAO
{
    private function mapObject(array $row): ItemPedido
    {
        $produtoDAO = new ProdutoDAO();
        $produto = $produtoDAO->getById($row['ProdutoId']);

        return new ItemPedido(
            $row['Id'], $row['PedidoId'], $produto,
            (int)$row['Quantidade'], (float)$row['PrecoUnitario'], (bool)$row['Ativo'],
            $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
        );
    }

    public function create(ItemPedido $item): bool
    {
        $sql = "INSERT INTO ItemPedido (PedidoId, ProdutoId, Quantidade, PrecoUnitario, UsuarioAtualizacao)
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

    public function getById(int $id): ?ItemPedido
    {
        $stmt = $this->db->prepare("SELECT * FROM ItemPedido WHERE Id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->mapObject($row) : null;
    }

    public function getByPedidoId(int $pedidoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM ItemPedido WHERE PedidoId = :pedido_id");
        $stmt->execute([':pedido_id' => $pedidoId]);

        $itens = [];
        foreach ($stmt->fetchAll() as $row) {
            $itens[] = $this->mapObject($row);
        }
        return $itens;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM ItemPedido" . ($somenteAtivos ? " WHERE Ativo = 1" : "");
        $stmt = $this->db->query($sql);
        
        $itens = [];
        foreach ($stmt->fetchAll() as $row) {
            $itens[] = $this->mapObject($row);
        }
        return $itens;
    }

    public function update(ItemPedido $item): bool
    {
        $sql = "UPDATE ItemPedido SET 
                    PedidoId = :pedido_id,
                    ProdutoId = :produto_id,
                    Quantidade = :quantidade,
                    PrecoUnitario = :preco_unitario,
                    UsuarioAtualizacao = :user_id,
                    DataAtualizacao = CURRENT_TIMESTAMP
                WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $item->getId(),
            ':pedido_id' => $item->getPedidoId(),
            ':produto_id' => $item->getProduto()->getId(),
            ':quantidade' => $item->getQuantidade(),
            ':preco_unitario' => $item->getPrecoUnitario(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE ItemPedido SET Ativo = 0, UsuarioAtualizacao = :user_id WHERE Id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM ItemPedido WHERE Id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
