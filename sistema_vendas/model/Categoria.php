<?php
class Categoria {

    public ?int $Id;
    public string $Nome;
    public ?string $Descricao;

    public function __construct(?int $Id=null, string $Nome='', ?string $Descricao=null) {
        $this->Id = $Id; $this->Nome = $Nome; $this->Descricao = $Descricao;
    }

    public function getId(): ?int { return $this->Id; }
    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}