<?php

class Pedido {
    public ?int $Id;
    public Cliente $Cliente;
    public ?string $DataPedido;
    public FormaPagamento $FormaPagamento;
    public string $Status;
    public array $Itens;
    public function __construct(?int $Id=null, ?Cliente $Cliente=null, ?string $DataPedido=null, ?FormaPagamento $FormaPagamento=null, string $Status='', array $Itens = []) {
        $this->Id = $Id; $this->Cliente = $Cliente ?? new Cliente(); $this->DataPedido = $DataPedido; $this->FormaPagamento = $FormaPagamento ?? new FormaPagamento(); $this->Status = $Status; $this->Itens = $Itens;
    }
    public function getTotal(): float {
        return array_reduce($this->Itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}
