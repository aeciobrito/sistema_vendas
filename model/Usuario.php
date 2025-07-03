<?php

class Usuario extends Entidade
{
    private string $nome_completo;
    private string $nome_usuario;
    private string $senha;
    private ?string $email;
    private ?string $telefone;
    private ?string $cpf;
    private bool $is_admin;
    private ?string $token;

    public function __construct(
        ?int $id, string $nome_completo, string $nome_usuario, string $senha, ?string $email, 
        ?string $telefone, ?string $cpf, bool $is_admin = false, bool $ativo = true, ?string $token = null, 
        ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->nome_completo = $nome_completo;
        $this->nome_usuario = $nome_usuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->is_admin = $is_admin;
        $this->token = $token;
    }

    public function getNomeCompleto(): string { return $this->nome_completo; }
    public function getNomeUsuario(): string { return $this->nome_usuario; }
    public function getSenha(): string { return $this->senha; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getCpf(): ?string { return $this->cpf; }
    public function isAdmin(): bool { return $this->is_admin; }
    public function getToken(): ?string { return $this->token; }
}
