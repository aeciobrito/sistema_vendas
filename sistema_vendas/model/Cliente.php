<?php

class Cliente {
    public ?int $Id;
    public string $Nome;
    public ?string $Email;
    public ?string $Telefone;
    public function __construct(?int $Id=null, string $Nome='', ?string $Email=null, ?string $Telefone=null) {
        $this->Id = $Id; $this->Nome = $Nome; $this->Email = $Email; $this->Telefone = $Telefone;
    }
}