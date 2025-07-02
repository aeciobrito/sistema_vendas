<?php

class Usuario extends Entidade
{
    private string $NomeUsuario;
    private string $Senha;
    private ?string $Email;
    private ?GrupoUsuario $GrupoUsuario;
    private ?string $Token;

    public function __construct(?int $Id, string $NomeUsuario, string $Senha, ?string $Email, ?GrupoUsuario $GrupoUsuario, bool $Ativo = true, ?string $Token = null, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->NomeUsuario = $NomeUsuario;
        $this->Senha = $Senha;
        $this->Email = $Email;
        $this->GrupoUsuario = $GrupoUsuario;
        $this->Token = $Token;
    }

    public function getNomeUsuario(): string { return $this->NomeUsuario; }
    public function getSenha(): string { return $this->Senha; }
    public function getEmail(): ?string { return $this->Email; }
    public function getGrupoUsuario(): ?GrupoUsuario { return $this->GrupoUsuario; }
    public function getToken(): ?string { return $this->Token; }
}