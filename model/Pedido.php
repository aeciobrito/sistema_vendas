<?php

class Pedido extends Entidade
{
    private Usuario $cliente;
    private string $dataPedido;
    private FormaPagamento $formaPagamento;
    private string $status;
    private array $itens;

    public function __construct(
        ?int $id, Usuario $cliente, string $dataPedido, FormaPagamento $formaPagamento, string $status, 
        bool $ativo = true, array $itens = [], ?string $dataCriacao = null, ?string $dataAtualizacao = null, 
        ?int $usuarioAtualizacao = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->cliente = $cliente;
        $this->dataPedido = $dataPedido;
        $this->formaPagamento = $formaPagamento;
        $this->status = $status;
        $this->itens = $itens;
    }

    public function getCliente(): Usuario { return $this->cliente; }
    public function getDataPedido(): string { return $this->dataPedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->formaPagamento; }
    public function getStatus(): string { return $this->status; }
    public function getItens(): array { return $this->itens; }
    public function setItens(array $itens): void { $this->itens = $itens; }
    
    public function getTotal(): float
    {
        return array_reduce($this->itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}
