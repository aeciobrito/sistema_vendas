<?php

/**
 * ItemPedido não herda de Entidade, pois é um objeto de valor
 * fortemente acoplado a um Pedido. Seus dados de auditoria são menos relevantes
 * que os do Pedido principal.
 */
class ItemPedido
{
    private ?int $id;
    private int $pedido_id;
    private Produto $produto;
    private int $quantidade;
    private float $preco_unitario;

    public function __construct(?int $id, int $pedido_id, Produto $produto, int $quantidade, float $preco_unitario)
    {
        $this->id = $id;
        $this->pedido_id = $pedido_id;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->preco_unitario = $preco_unitario;
    }

    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): int { return $this->pedido_id; }
    public function getProduto(): Produto { return $this->produto; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->preco_unitario; }
    
    public function getSubtotal(): float
    {
        return $this->preco_unitario * $this->quantidade;
    }
}