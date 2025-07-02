<?php

class PermissaoGrupo
{
    private int $PermissaoID;
    private int $GrupoUsuarioID;

    public function __construct(int $PermissaoID, int $GrupoUsuarioID)
    {
        $this->PermissaoID = $PermissaoID;
        $this->GrupoUsuarioID = $GrupoUsuarioID;
    }

    public function getPermissaoID(): int
    {
        return $this->PermissaoID;
    }

    public function getGrupoUsuarioID(): int
    {
        return $this->GrupoUsuarioID;
    }
}
