<?php

/**
 * Classe base abstrata para todas as entidades do sistema.
 * Contém campos comuns de auditoria e controle.
 * Os nomes das propriedades correspondem aos nomes das colunas no banco de dados.
 */
class Entidade
{
    protected ?int $id;
    protected ?string $dataCriacao;
    protected ?string $dataAtualizacao;
    protected ?int $usuarioAtualizacao;
    protected bool $ativo;

    public function __construct(?int $id, bool $ativo, ?string $dataCriacao, ?string $dataAtualizacao, ?int $usuarioAtualizacao)
    {
        $this->id = $id;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->usuarioAtualizacao = $usuarioAtualizacao;
    }

    public function getId(): ?int { return $this->id; }
    public function isAtivo(): bool { return $this->ativo; }
    public function getDataCriacao(): ?string { return $this->dataCriacao; }
    public function getDataAtualizacao(): ?string { return $this->dataAtualizacao; }
    public function getUsuarioAtualizacao(): ?int { return $this->usuarioAtualizacao; }
}
