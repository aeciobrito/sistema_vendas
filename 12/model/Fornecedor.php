<?php

class Fornecedor implements JsonSerializable
{
    private ?int $id;
    private string $nome;
    private ?string $cnpj;
    private ?string $contato;

    public function __construct(?int $id, string $nome, ?string $cnpj, ?string $contato)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cnpj = $cnpj;
        $this->contato = $contato;
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getCnpj(): ?string { return $this->cnpj; }
    public function getContato(): ?string { return $this->contato; }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'cnpj' => $this->cnpj,
            'contato' => $this->contato,
        ];
    }
}
