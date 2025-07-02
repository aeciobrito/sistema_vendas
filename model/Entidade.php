<?php

/**
 * Classe base abstrata para todas as entidades do sistema.
 * Contém campos comuns de auditoria e controle.
 */
abstract class Entidade
{
    protected ?int $Id;
    protected ?string $DataCriacao;
    protected ?string $DataAtualizacao;
    protected ?int $UsuarioAtualizacao;
    protected bool $Ativo;

    public function __construct(?int $Id, bool $Ativo, ?string $DataCriacao, ?string $DataAtualizacao, ?int $UsuarioAtualizacao)
    {
        $this->Id = $Id;
        $this->Ativo = $Ativo;
        $this->DataCriacao = $DataCriacao;
        $this->DataAtualizacao = $DataAtualizacao;
        $this->UsuarioAtualizacao = $UsuarioAtualizacao;
    }

    public function getId(): ?int { return $this->Id; }
    public function isAtivo(): bool { return $this->Ativo; }
    public function getDataCriacao(): ?string { return $this->DataCriacao; }
    public function getDataAtualizacao(): ?string { return $this->DataAtualizacao; }
    public function getUsuarioAtualizacao(): ?int { return $this->UsuarioAtualizacao; }
}
