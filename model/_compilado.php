<?php

/**
 * Classe base abstrata para todas as entidades do sistema.
 * Contém campos comuns de auditoria e controle.
 * Os nomes das propriedades correspondem aos nomes das colunas no banco de dados.
 */
abstract class Entidade
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

class Categoria extends Entidade
{
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null)
    {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}

class FormaPagamento extends Entidade
{
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null)
    {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}

class Usuario extends Entidade
{
    private string $nomeCompleto;
    private string $nomeUsuario;
    private string $senha;
    private ?string $email;
    private ?string $telefone;
    private ?string $cpf;
    private bool $isAdmin;
    private ?string $token;

    public function __construct(
        ?int $id, string $nomeCompleto, string $nomeUsuario, string $senha, ?string $email, 
        ?string $telefone, ?string $cpf, bool $isAdmin = false, bool $ativo = true, ?string $token = null, 
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nomeCompleto = $nomeCompleto;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->isAdmin = $isAdmin;
        $this->token = $token;
    }

    public function getNomeCompleto(): string { return $this->nomeCompleto; }
    public function getNomeUsuario(): string { return $this->nomeUsuario; }
    public function getSenha(): string { return $this->senha; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getCpf(): ?string { return $this->cpf; }
    public function isAdmin(): bool { return $this->isAdmin; }
    public function getToken(): ?string { return $this->token; }
}

class Produto extends Entidade
{
    private string $nome;
    private ?string $descricao;
    private float $preco;
    private ?Categoria $categoria;

    public function __construct(
        ?int $id, string $nome, ?string $descricao, float $preco, ?Categoria $categoria, bool $ativo = true, 
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPreco(): float { return $this->preco; }
    public function getCategoria(): ?Categoria { return $this->categoria; }
}

class Pedido extends Entidade
{
    private Usuario $cliente; // O cliente agora é um objeto Usuario
    private string $dataPedido;
    private FormaPagamento $formaPagamento;
    private string $status;
    private array $itens;

    public function __construct(
        ?int $id, Usuario $cliente, string $dataPedido, FormaPagamento $formaPagamento, string $status, 
        bool $ativo = true, array $itens = [], ?string $dataCriacao = null, ?string $dataAtualizacao = null, 
        ?int $usuarioAtualizacao = null
    ) {
        parent::__construct($id, $ativo, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao);
        $this->cliente = $cliente;
        $this->dataPedido = $dataPedido;
        $this->formaPagamento = $formaPagamento;
        $this->status = $status;
        $this->itens = $itens;
    }

    public function getCliente(): Usuario { return $this->cliente; }
    public function getDataPedido(): string { return $this->dataPedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->formaPagamento; }
    public function getStatus(): string { return $this->status; }
    public function getItens(): array { return $this->itens; }
    public function setItens(array $itens): void { $this->itens = $itens; }
    
    public function getTotal(): float
    {
        return array_reduce($this->itens, fn($total, $item) => $total + $item->getSubtotal(), 0.0);
    }
}

/**
 * ItemPedido não herda de Entidade, pois é um objeto de valor
 * fortemente acoplado a um Pedido. Seus dados de auditoria são menos relevantes
 * que os do Pedido principal.
 */
class ItemPedido
{
    private ?int $id;
    private int $pedidoId;
    private Produto $produto;
    private int $quantidade;
    private float $precoUnitario;

    public function __construct(?int $id, int $pedidoId, Produto $produto, int $quantidade, float $precoUnitario)
    {
        $this->id = $id;
        $this->pedidoId = $pedidoId;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->precoUnitario = $precoUnitario;
    }

    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): int { return $this->pedidoId; }
    public function getProduto(): Produto { return $this->produto; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->precoUnitario; }
    
    public function getSubtotal(): float
    {
        return $this->precoUnitario * $this->quantidade;
    }
}