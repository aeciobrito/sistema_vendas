<?php

class Produto extends Entidade
{
    private string $nome;
    private ?string $descricao;
    private float $preco;
    private ?Categoria $categoria;

    public function __construct(
        ?int $id, string $nome, ?string $descricao, float $preco, ?Categoria $categoria, bool $ativo = true, 
        ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPreco(): float { return $this->preco; }
    public function getCategoria(): ?Categoria { return $this->categoria; }
}