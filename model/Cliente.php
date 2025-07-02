<?php


class Cliente extends Entidade
{
    private string $Nome;
    private ?string $Email;
    private ?string $Telefone;

    public function __construct(?int $Id, string $Nome, ?string $Email, ?string $Telefone, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Email = $Email;
        $this->Telefone = $Telefone;
    }

    public function getNome(): string { return $this->Nome; }
    public function getEmail(): ?string { return $this->Email; }
    public function getTelefone(): ?string { return $this->Telefone; }
}