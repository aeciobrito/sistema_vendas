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

class Categoria extends Entidade
{
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}

class Cliente extends Entidade
{
    private string $Nome;
    private ?string $Email;
    private ?string $Telefone;

    public function __construct(?int $Id, string $Nome, ?string $Email, ?string $Telefone, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Email = $Email;
        $this->Telefone = $Telefone;
    }

    public function getNome(): string { return $this->Nome; }
    public function getEmail(): ?string { return $this->Email; }
    public function getTelefone(): ?string { return $this->Telefone; }
}

class FormaPagamento extends Entidade
{
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}

class GrupoUsuario extends Entidade
{
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}

class ItemPedido extends Entidade
{
    private int $PedidoId;
    private Produto $Produto;
    private int $Quantidade;
    private float $PrecoUnitario;

    public function __construct(?int $Id, int $PedidoId, Produto $Produto, int $Quantidade, float $PrecoUnitario, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->PedidoId = $PedidoId;
        $this->Produto = $Produto;
        $this->Quantidade = $Quantidade;
        $this->PrecoUnitario = $PrecoUnitario;
    }

    public function getPedidoId(): int { return $this->PedidoId; }
    public function getProduto(): Produto { return $this->Produto; }
    public function getQuantidade(): int { return $this->Quantidade; }
    public function getPrecoUnitario(): float { return $this->PrecoUnitario; }
    public function getSubtotal(): float { return $this->PrecoUnitario * $this->Quantidade; }
}

class Pedido extends Entidade
{
    private Cliente $Cliente;
    private string $DataPedido;
    private FormaPagamento $FormaPagamento;
    private string $Status;
    private array $Itens;

    public function __construct(?int $Id, Cliente $Cliente, string $DataPedido, FormaPagamento $FormaPagamento, string $Status, bool $Ativo = true, array $Itens = [], ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Cliente = $Cliente;
        $this->DataPedido = $DataPedido;
        $this->FormaPagamento = $FormaPagamento;
        $this->Status = $Status;
        $this->Itens = $Itens;
    }

    public function getCliente(): Cliente { return $this->Cliente; }
    public function getDataPedido(): string { return $this->DataPedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->FormaPagamento; }
    public function getStatus(): string { return $this->Status; }
    public function getItens(): array { return $this->Itens; }
    public function setItens(array $itens): void { $this->Itens = $itens; }
    public function getTotal(): float
    {
        return array_reduce($this->Itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}

class Permissao extends Entidade
{
    private string $Nome;
    private ?string $Descricao;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
}

class PermissaoGrupo extends Entidade
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

class Produto extends Entidade
{
    private string $Nome;
    private ?string $Descricao;
    private float $Preco;
    private ?Categoria $Categoria;

    public function __construct(?int $Id, string $Nome, ?string $Descricao, float $Preco, ?Categoria $Categoria, bool $Ativo = true, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->Nome = $Nome;
        $this->Descricao = $Descricao;
        $this->Preco = $Preco;
        $this->Categoria = $Categoria;
    }

    public function getNome(): string { return $this->Nome; }
    public function getDescricao(): ?string { return $this->Descricao; }
    public function getPreco(): float { return $this->Preco; }
    public function getCategoria(): ?Categoria { return $this->Categoria; }
}

class Usuario extends Entidade
{
    private string $NomeUsuario;
    private string $Senha;
    private ?string $Email;
    private ?GrupoUsuario $GrupoUsuario;
    private ?string $Token;

    public function __construct(?int $Id, string $NomeUsuario, string $Senha, ?string $Email, ?GrupoUsuario $GrupoUsuario, bool $Ativo = true, ?string $Token = null, ?string $DataCriacao = null, ?string $DataAtualizacao = null, ?int $UsuarioAtualizacao = null)
    {
        parent::__construct($Id, $Ativo, $DataCriacao, $DataAtualizacao, $UsuarioAtualizacao);
        $this->NomeUsuario = $NomeUsuario;
        $this->Senha = $Senha;
        $this->Email = $Email;
        $this->GrupoUsuario = $GrupoUsuario;
        $this->Token = $Token;
    }

    public function getNomeUsuario(): string { return $this->NomeUsuario; }
    public function getSenha(): string { return $this->Senha; }
    public function getEmail(): ?string { return $this->Email; }
    public function getGrupoUsuario(): ?GrupoUsuario { return $this->GrupoUsuario; }
    public function getToken(): ?string { return $this->Token; }
}