<?php

class Usuario implements JsonSerializable
{
    private ?int $id;
    private string $nomeUsuario;
    private string $senha;
    private string $email;
    private ?string $token;

    public function __construct(?int $id, string $nomeUsuario, string $senha, string $email, ?string $token = null)
    {
        $this->id = $id;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->token = $token;
    }

    public function getId(): ?int { return $this->id; }
    public function getNomeUsuario(): string { return $this->nomeUsuario; }
    public function getSenha(): string { return $this->senha; }
    public function getEmail(): string { return $this->email; }
    public function getToken(): ?string { return $this->token; }

    public function setToken(?string $token): void { $this->token = $token; }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'nomeUsuario' => $this->nomeUsuario,
            'email' => $this->email,
            'token' => $this->token
        ];
    }
}
