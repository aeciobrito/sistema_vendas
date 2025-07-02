<?php
class Categoria extends Entidade
{
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}