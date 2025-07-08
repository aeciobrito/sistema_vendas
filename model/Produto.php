<?php

require_once __DIR__ . '/Entidade.php';

class Produto extends Entidade
{
    private string $nome;
    private ?string $descricao;
    private float $preco;
    private ?Categoria $categoria;

    public function __construct(
        ?int $id, string $nome, ?string $descricao, float $preco, ?Categoria $categoria, bool $ativo = true, 
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?Usuario $usuarioAtualizacao = null,
        ?string $imagemUrl = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPreco(): float { return $this->preco; }
    public function getCategoria(): ?Categoria { return $this->categoria; }

    public function getImagemUrl(): string 
    {
        return $this->imagemUrl ?? 'https://static.vecteezy.com/system/resources/thumbnails/004/141/669/small_2x/no-photo-or-blank-image-icon-loading-images-or-missing-image-mark-image-not-available-or-image-coming-soon-sign-simple-nature-silhouette-in-frame-isolated-illustration-vector.jpg';
    }
}