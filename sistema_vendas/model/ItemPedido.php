<?php

class ItemPedido {
    public ?int $Id;
    public int $PedidoId;
    public Produto $Produto;
    public int $Quantidade;
    public function __construct(?int $Id=null, int $PedidoId=0, ?Produto $Produto=null, int $Quantidade=0) {
        $this->Id = $Id; $this->PedidoId = $PedidoId; $this->Produto = $Produto ?? new Produto(); $this->Quantidade = $Quantidade;
    }
    public function getSubtotal(): float { return $this->Produto->Preco * $this->Quantidade; }
}