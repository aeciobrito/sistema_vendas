<?php

require_once __DIR__ . '/Fornecedor.php';

class Produto implements JsonSerializable
{
    private ?int $id;
    private string $nome;
    private float $preco;
    private bool $ativo;
    private string $dataDeCadastro;
    private ?string $dataDeValidade;

    private ?Fornecedor $fornecedor;

    public function __construct(?int $id, string $nome, float $preco, bool $ativo, string $dataDeCadastro, ?string $dataDeValidade, ?Fornecedor $fornecedor)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->ativo = $ativo;
        $this->dataDeCadastro = $dataDeCadastro;
        $this->dataDeValidade = $dataDeValidade;
        $this->fornecedor = $fornecedor; 
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getPreco(): float { return $this->preco; }
    public function getAtivo(): bool { return $this->ativo; }
    public function getDataDeCadastro(): string { return $this->dataDeCadastro; }
    public function getDataDeValidade(): ?string { return $this->dataDeValidade; }
    
    public function getFornecedor(): ?Fornecedor { return $this->fornecedor; }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'preco' => $this->preco,
            'ativo' => $this->ativo,
            'dataDeCadastro' => $this->dataDeCadastro,
            'dataDeValidade' => $this->dataDeValidade,
            'fornecedor' => $this->fornecedor ? $this->fornecedor->jsonSerialize() : null,
        ];
    }
}
