<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../model/Entidade.php';
require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/../model/FormaPagamento.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';

require_once __DIR__ . '/../core/Database.php';

class CategoriaDAO
{
    private function mapObject(array $row): Categoria
    {
        return new Categoria(
            $row['id'], $row['nome'], $row['descricao'], (bool)$row['ativo'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }

    public function create(Categoria $categoria): bool
    {
        $sql = "INSERT INTO categoria (nome, descricao, usuario_atualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => 1 // Placeholder
        ]);
    }

    public function getById(int $id): ?Categoria
    {
        $stmt = $this->db->prepare("SELECT * FROM categoria WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM categoria" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Categoria $categoria): bool
    {
        $sql = "UPDATE categoria SET nome = :nome, descricao = :descricao, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $categoria->getId(),
            ':nome' => $categoria->getNome(),
            ':descricao' => $categoria->getDescricao(),
            ':user_id' => 1 // Placeholder
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE categoria SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

class FormaPagamentoDAO
{
    private function mapObject(array $row): FormaPagamento
    {
        return new FormaPagamento(
            $row['id'], $row['nome'], $row['descricao'], (bool)$row['ativo'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }

    public function create(FormaPagamento $formaPagamento): bool
    {
        $sql = "INSERT INTO forma_pagamento (nome, descricao, usuario_atualizacao) VALUES (:nome, :descricao, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?FormaPagamento
    {
        $stmt = $this->db->prepare("SELECT * FROM forma_pagamento WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM forma_pagamento" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(FormaPagamento $formaPagamento): bool
    {
        $sql = "UPDATE forma_pagamento SET nome = :nome, descricao = :descricao, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $formaPagamento->getId(),
            ':nome' => $formaPagamento->getNome(),
            ':descricao' => $formaPagamento->getDescricao(),
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE forma_pagamento SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM forma_pagamento WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

// -----------------------------------------------------------------------------
// DAO para Entidades com Relacionamentos
// -----------------------------------------------------------------------------

class UsuarioDAO
{
    private function mapObject(array $row): Usuario
    {
        return new Usuario(
            $row['id'], $row['nome_completo'], $row['nome_usuario'], $row['senha'], $row['email'],
            $row['telefone'], $row['cpf'], (bool)$row['is_admin'], (bool)$row['ativo'], $row['token'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }
    
    public function create(Usuario $usuario): bool
    {
        $sql = "INSERT INTO usuario (nome_completo, nome_usuario, senha, email, telefone, cpf, is_admin, usuario_atualizacao) 
                VALUES (:nome_completo, :nome_usuario, :senha, :email, :telefone, :cpf, :is_admin, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(), // A senha já deve vir com hash
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => $usuario->isAdmin() ? 1 : 0,
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM usuario" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome_completo";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE usuario SET 
                    nome_completo = :nome_completo, nome_usuario = :nome_usuario, email = :email, 
                    telefone = :telefone, cpf = :cpf, is_admin = :is_admin, usuario_atualizacao = :user_id 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $usuario->getId(),
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => $usuario->isAdmin() ? 1 : 0,
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE usuario SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

class ProdutoDAO
{
    private function mapObject(array $row): Produto
    {
        $categoriaDAO = new CategoriaDAO();
        $categoria = isset($row['categoria_id']) ? $categoriaDAO->getById($row['categoria_id']) : null;

        return new Produto(
            $row['id'], $row['nome'], $row['descricao'], (float)$row['preco'], $categoria, (bool)$row['ativo'],
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }

    public function create(Produto $produto): bool
    {
        $sql = "INSERT INTO produto (nome, descricao, preco, categoria_id, usuario_atualizacao) 
                VALUES (:nome, :descricao, :preco, :categoria_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $produto->getCategoria() ? $produto->getCategoria()->getId() : null,
            ':user_id' => 1
        ]);
    }

    public function getById(int $id): ?Produto
    {
        $stmt = $this->db->prepare("SELECT * FROM produto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM produto" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY nome";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function update(Produto $produto): bool
    {
        $sql = "UPDATE produto SET nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $produto->getId(),
            ':nome' => $produto->getNome(),
            ':descricao' => $produto->getDescricao(),
            ':preco' => $produto->getPreco(),
            ':categoria_id' => $produto->getCategoria() ? $produto->getCategoria()->getId() : null,
            ':user_id' => 1
        ]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE produto SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produto WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

class ItemPedidoDAO
{
    private function mapObject(array $row): ItemPedido
    {
        $produtoDAO = new ProdutoDAO();
        $produto = $produtoDAO->getById($row['produto_id']);

        return new ItemPedido(
            $row['id'], $row['pedido_id'], $produto, (int)$row['quantidade'], (float)$row['preco_unitario']
        );
    }

    public function create(ItemPedido $item): bool
    {
        $sql = "INSERT INTO item_pedido (pedido_id, produto_id, quantidade, preco_unitario, usuario_atualizacao) 
                VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario, :user_id)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':pedido_id' => $item->getPedidoId(),
            ':produto_id' => $item->getProduto()->getId(),
            ':quantidade' => $item->getQuantidade(),
            ':preco_unitario' => $item->getPrecoUnitario(),
            ':user_id' => 1
        ]);
    }
    
    public function getByPedidoId(int $pedidoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM item_pedido WHERE pedido_id = :pedido_id");
        $stmt->execute([':pedido_id' => $pedidoId]);
        $itens = [];
        foreach ($stmt->fetchAll() as $row) {
            $itens[] = $this->mapObject($row);
        }
        return $itens;
    }
}

class PedidoDAO
{
    private function mapObject(array $row): Pedido
    {
        $usuarioDAO = new UsuarioDAO();
        $formaPagamentoDAO = new FormaPagamentoDAO();
        $itemPedidoDAO = new ItemPedidoDAO();

        $cliente = $usuarioDAO->getById($row['cliente_id']);
        $formaPagamento = $formaPagamentoDAO->getById($row['forma_pagamento_id']);
        $itens = $itemPedidoDAO->getByPedidoId($row['id']);

        return new Pedido(
            $row['id'], $cliente, $row['data_pedido'], $formaPagamento, $row['status'], (bool)$row['ativo'], $itens,
            $row['data_criacao'], $row['data_atualizacao'], $row['usuario_atualizacao']
        );
    }
    
    public function create(Pedido $pedido): ?int
    {
        try {
            $this->db->beginTransaction();

            $sqlPedido = "INSERT INTO pedido (cliente_id, data_pedido, forma_pagamento_id, status, usuario_atualizacao) 
                          VALUES (:cliente_id, :data_pedido, :forma_pagamento_id, :status, :user_id)";
            $stmtPedido = $this->db->prepare($sqlPedido);
            $stmtPedido->execute([
                ':cliente_id' => $pedido->getCliente()->getId(),
                ':data_pedido' => $pedido->getDataPedido(),
                ':forma_pagamento_id' => $pedido->getFormaPagamento()->getId(),
                ':status' => $pedido->getStatus(),
                ':user_id' => 1
            ]);
            $pedidoId = (int)$this->db->lastInsertId();

            $itemPedidoDAO = new ItemPedidoDAO();
            foreach ($pedido->getItens() as $item) {
                $itemParaSalvar = new ItemPedido(
                    null, $pedidoId, $item->getProduto(), $item->getQuantidade(), $item->getPrecoUnitario()
                );
                $itemPedidoDAO->create($itemParaSalvar);
            }

            $this->db->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return null;
        }
    }

    public function getById(int $id): ?Pedido
    {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM pedido" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY data_pedido DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) { $result[] = $this->mapObject($row); }
        return $result;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $sql = "UPDATE pedido SET status = :status, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status, ':user_id' => 1]);
    }

    public function softDelete(int $id): bool
    {
        $sql = "UPDATE pedido SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => 1]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pedido WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
