<?php

class Produto extends Entidade
{
    private string $Nome;
    private ?string $Descricao;
    private float $Preco;
    private ?Categoria $Categoria;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, float $Preco, ?Categoria $Categoria, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
        $this->Preco = $Preco;
        $this->Categoria = $Categoria;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
    public function getPreco(): float { return $this->Preco; }
    public function getCategoria(): ?Categoria { return $this->Categoria; }
}