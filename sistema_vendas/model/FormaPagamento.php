<?php

class FormaPagamento {
    public ?int $Id;
    public string $Nome;
    public ?string $Descricao;
    public function __construct(?int $Id=null, string $Nome='', ?string $Descricao=null) {
        $this->Id = $Id; $this->Nome = $Nome; $this->Descricao = $Descricao;
    }
}