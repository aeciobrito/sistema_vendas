<?php

class Usuario {
    public ?int $Id;
    public string $NomeUsuario;
    public string $Senha;
    public ?string $Email;
    public ?GrupoUsuario $GrupoUsuario;
    public ?string $Token;
    public function __construct(?int $Id=null, string $NomeUsuario='', string $Senha='', ?string $Email=null, ?GrupoUsuario $GrupoUsuario=null, ?string $Token=null) {
        $this->Id = $Id; $this->NomeUsuario = $NomeUsuario; $this->Senha = $Senha; $this->Email = $Email; $this->GrupoUsuario = $GrupoUsuario; $this->Token = $Token;
    }
}