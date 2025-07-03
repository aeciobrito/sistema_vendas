<?php

class Pedido extends Entidade
{
    private Usuario $cliente;
    private string $data_pedido;
    private FormaPagamento $forma_pagamento;
    private string $status;
    private array $itens;

    public function __construct(
        ?int $id, Usuario $cliente, string $data_pedido, FormaPagamento $forma_pagamento, string $status, 
        bool $ativo = true, array $itens = [], ?string $data_criacao = null, ?string $data_atualizacao = null, 
        ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->cliente = $cliente;
        $this->data_pedido = $data_pedido;
        $this->forma_pagamento = $forma_pagamento;
        $this->status = $status;
        $this->itens = $itens;
    }

    public function getCliente(): Usuario { return $this->cliente; }
    public function getDataPedido(): string { return $this->data_pedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->forma_pagamento; }
    public function getStatus(): string { return $this->status; }
    public function getItens(): array { return $this->itens; }
    public function setItens(array $itens): void { $this->itens = $itens; }
    
    public function getTotal(): float
    {
        return array_reduce($this->itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}
