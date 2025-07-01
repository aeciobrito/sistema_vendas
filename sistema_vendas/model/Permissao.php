<?php

class Permissao
{
    private ?int $Id;
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao)
    {
        $this->Id = $Id;
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getId(): ?int { return $this->Id; }
    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}