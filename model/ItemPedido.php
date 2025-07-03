<?php

/**
 * ItemPedido não herda de Entidade, pois é um objeto de valor
 * fortemente acoplado a um Pedido. Seus dados de auditoria são menos relevantes
 * que os do Pedido principal.
 */
class ItemPedido
{
    private ?int $id;
    private int $pedidoId;
    private Produto $produto;
    private int $quantidade;
    private float $precoUnitario;

    public function __construct(?int $id, int $pedidoId, Produto $produto, int $quantidade, float $precoUnitario)
    {
        $this->id = $id;
        $this->pedidoId = $pedidoId;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->precoUnitario = $precoUnitario;
    }

    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): int { return $this->pedidoId; }
    public function getProduto(): Produto { return $this->produto; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->precoUnitario; }
    
    public function getSubtotal(): float
    {
        return $this->precoUnitario * $this->quantidade;
    }
}