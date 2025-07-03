<?php

/**
 * Classe base abstrata para todas as entidades do sistema.
 * Contém campos comuns de auditoria e controle.
 * Os nomes das propriedades correspondem aos nomes das colunas no banco de dados.
 */
abstract class Entidade
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

class Categoria extends Entidade
{
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null)
    {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
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

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true, ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null)
    {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}

class Usuario extends Entidade
{
    private string $nome_completo;
    private string $nome_usuario;
    private string $senha;
    private ?string $email;
    private ?string $telefone;
    private ?string $cpf;
    private bool $is_admin;
    private ?string $token;

    public function __construct(
        ?int $id, string $nome_completo, string $nome_usuario, string $senha, ?string $email, 
        ?string $telefone, ?string $cpf, bool $is_admin = false, bool $ativo = true, ?string $token = null, 
        ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->nome_completo = $nome_completo;
        $this->nome_usuario = $nome_usuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->is_admin = $is_admin;
        $this->token = $token;
    }

    public function getNomeCompleto(): string { return $this->nome_completo; }
    public function getNomeUsuario(): string { return $this->nome_usuario; }
    public function getSenha(): string { return $this->senha; }
    public function getEmail(): ?string { return $this->email; }
    public function getTelefone(): ?string { return $this->telefone; }
    public function getCpf(): ?string { return $this->cpf; }
    public function isAdmin(): bool { return $this->is_admin; }
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
        ?string $data_criacao = null, ?string $data_atualizacao = null, ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
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
    private string $data_pedido;
    private FormaPagamento $forma_pagamento;
    private string $status;
    private array $itens;

    public function __construct(
        ?int $id, Usuario $cliente, string $data_pedido, FormaPagamento $forma_pagamento, string $status, 
        bool $ativo = true, array $itens = [], ?string $data_criacao = null, ?string $data_atualizacao = null, 
        ?int $usuario_atualizacao = null
    ) {
        parent::__construct($id, $ativo, $data_criacao, $data_atualizacao, $usuario_atualizacao);
        $this->cliente = $cliente;
        $this->data_pedido = $data_pedido;
        $this->forma_pagamento = $forma_pagamento;
        $this->status = $status;
        $this->itens = $itens;
    }

    public function getCliente(): Usuario { return $this->cliente; }
    public function getDataPedido(): string { return $this->data_pedido; }
    public function getFormaPagamento(): FormaPagamento { return $this->forma_pagamento; }
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
    private int $pedido_id;
    private Produto $produto;
    private int $quantidade;
    private float $preco_unitario;

    public function __construct(?int $id, int $pedido_id, Produto $produto, int $quantidade, float $preco_unitario)
    {
        $this->id = $id;
        $this->pedido_id = $pedido_id;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->preco_unitario = $preco_unitario;
    }

    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): int { return $this->pedido_id; }
    public function getProduto(): Produto { return $this->produto; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoUnitario(): float { return $this->preco_unitario; }
    
    public function getSubtotal(): float
    {
        return $this->preco_unitario * $this->quantidade;
    }
}