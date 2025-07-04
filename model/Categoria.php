<?php
class Categoria extends Entidade
{
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?Usuario $usuarioAtualizacao = null)
    {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}