<?php
class Categoria extends Entidade
{
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null)
    {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}