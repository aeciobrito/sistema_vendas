<?php

class Pedido extends Entidade
{
    private Cliente $Cliente;
    private string $DataPedido;
    private FormaPagamento $FormaPagamento;
    private string $Status;
    private array $Itens;

    public function __construct(?int $Id, Cliente $Cliente, string $DataPedido, FormaPagamento $FormaPagamento, string $Status, bool $Ativo = true, array $Itens = [], ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Cliente = $Cliente;
        $this->DataPedido = $DataPedido;
        $this->FormaPagamento = $FormaPagamento;
        $this->Status = $Status;
        $this->Itens = $Itens;
    }

    public function getCliente(): Cliente { return $this->Cliente; }
    public function getDataPedido(): string { return $this->DataPedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->FormaPagamento; }
    public function getStatus(): string { return $this->Status; }
    public function getItens(): array { return $this->Itens; }
    public function setItens(array $itens): void { $this->Itens = $itens; }
    public function getTotal(): float
    {
        return array_reduce($this->Itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}