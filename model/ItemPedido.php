<?php

class ItemPedido extends Entidade
{
    private int $PedidoId;
    private Produto $Produto;
    private int $Quantidade;
    private float $PrecoUnitario;

    public function __construct(?int $Id, int $PedidoId, Produto $Produto, int $Quantidade, float $PrecoUnitario, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->PedidoId = $PedidoId;
        $this->Produto = $Produto;
        $this->Quantidade = $Quantidade;
        $this->PrecoUnitario = $PrecoUnitario;
    }

    public function getPedidoId(): int { return $this->PedidoId; }
    public function getProduto(): Produto { return $this->Produto; }
    public function getQuantidade(): int { return $this->Quantidade; }
    public function getPrecoUnitario(): float { return $this->PrecoUnitario; }
    public function getSubtotal(): float { return $this->PrecoUnitario * $this->Quantidade; }
}