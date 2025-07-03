<?php

class Usuario extends Entidade
{
    private string $nomeCompleto;
    private string $nomeUsuario;
    private string $senha;
    private ?string $email;
    private ?string $telefone;
    private ?string $cpf;
    private bool $isAdmin;
    private ?string $token;

    public function __construct(
        ?int $id, string $nomeCompleto, string $nomeUsuario, string $senha, ?string $email, 
        ?string $telefone, ?string $cpf, bool $isAdmin = false, bool $ativo = true, ?string $token = null, 
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nomeCompleto = $nomeCompleto;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->isAdmin = $isAdmin;
        $this->token = $token;
    }

    public function getNomeCompleto(): string { return $this->nomeCompleto; }
    public function getNomeUsuario(): string { return $this->nomeUsuario; }
    public function getSenha(): string { return $this->senha; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getCpf(): ?string { return $this->cpf; }
    public function isAdmin(): bool { return $this->isAdmin; }
    public function getToken(): ?string { return $this->token; }
}
