<?php

/**
 * Classe base abstrata para todas as entidades do sistema.
 * Contém campos comuns de auditoria e controle.
 * Os nomes das propriedades correspondem aos nomes das colunas no banco de dados.
 */
class Entidade
{
    protected ?int $id;
    protected ?string $data_criacao;
    protected ?string $data_atualizacao;
    protected ?int $usuario_atualizacao;
    protected bool $ativo;

    public function __construct(?int $id, bool $ativo, ?string $data_criacao, ?string $data_atualizacao, ?int $usuario_atualizacao)
    {
        $this->id = $id;
        $this->ativo = $ativo;
        $this->data_criacao = $data_criacao;
        $this->data_atualizacao = $data_atualizacao;
        $this->usuario_atualizacao = $usuario_atualizacao;
    }

    public function getId(): ?int { return $this->id; }
    public function isAtivo(): bool { return $this->ativo; }
    public function getDataCriacao(): ?string { return $this->data_criacao; }
    public function getDataAtualizacao(): ?string { return $this->data_atualizacao; }
    public function getUsuarioAtualizacao(): ?int { return $this->usuario_atualizacao; }
}
