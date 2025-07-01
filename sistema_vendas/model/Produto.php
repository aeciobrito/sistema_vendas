<?php

class Produto {
    public ?int $Id;
    public string $Nome;
    public ?string $Descricao;
    public float $Preco;
    public ?Categoria $Categoria;
    public function __construct(?int $Id=null, string $Nome='', ?string $Descricao=null, float $Preco=0.0, ?Categoria $Categoria=null) {
        $this->Id = $Id; $this->Nome = $Nome; $this->Descricao = $Descricao; $this->Preco = $Preco; $this->Categoria = $Categoria;
    }
}