<?php
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../model/Fornecedor.php'; // Incluir o model de Fornecedor
require_once __DIR__ . '/../database/Database.php';

class ProdutoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mapeia um array de dados do banco para um objeto Produto, incluindo o Fornecedor.
     * @param array $data O array de dados vindo do fetch.
     * @return Produto O objeto Produto construído.
     */
    private function mapProduto(array $data): Produto
    {
        $fornecedor = null;
        // Verifica se os dados do fornecedor foram retornados pela query
        if (isset($data['fornecedor_id']) && $data['fornecedor_id']) {
            $fornecedor = new Fornecedor(
                (int)$data['fornecedor_id'],
                $data['fornecedor_nome'],
                $data['fornecedor_cnpj'],
                $data['fornecedor_contato']
            );
        }

        return new Produto(
            (int)$data['id'],
            $data['nome'],
            (float)$data['preco'],
            (bool)$data['ativo'],
            $data['dataDeCadastro'],
            $data['dataDeValidade'],
            $fornecedor // Passa o objeto Fornecedor completo
        );
    }

    public function getAll(): array
    {
        // Query com LEFT JOIN para buscar o fornecedor associado
        $sql = "SELECT 
                    p.*,
                    f.id AS fornecedor_id, 
                    f.nome AS fornecedor_nome,
                    f.cnpj AS fornecedor_cnpj,
                    f.contato AS fornecedor_contato
                FROM produtos p
                LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
                ORDER BY p.nome ASC";
        
        $stmt = $this->db->query($sql);
        $produtosData = $stmt->fetchAll();
        $produtos = [];
        foreach ($produtosData as $data) {
            $produtos[] = $this->mapProduto($data);
        }
        return $produtos;
    }
    
    public function getById(int $id): ?Produto
    {
        $sql = "SELECT 
                    p.*,
                    f.id AS fornecedor_id, 
                    f.nome AS fornecedor_nome,
                    f.cnpj AS fornecedor_cnpj,
                    f.contato AS fornecedor_contato
                FROM produtos p
                LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
                WHERE p.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();
        
        return $data ? $this->mapProduto($data) : null;
    }

    public function create(Produto $produto): bool
    {
        // Adiciona fornecedor_id ao INSERT
        $sql = "INSERT INTO produtos (nome, preco, ativo, dataDeCadastro, dataDeValidade, fornecedor_id) 
                VALUES (:nome, :preco, :ativo, :dataDeCadastro, :dataDeValidade, :fornecedor_id)";
        $stmt = $this->db->prepare($sql);
        
        // Extrai o ID do objeto Fornecedor, se ele existir
        $fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : null;

        return $stmt->execute([
            ':nome' => $produto->getNome(),
            ':preco' => $produto->getPreco(),
            ':ativo' => $produto->getAtivo() ? 1 : 0,
            ':dataDeCadastro' => $produto->getDataDeCadastro(),
            ':dataDeValidade' => $produto->getDataDeValidade(),
            ':fornecedor_id' => $fornecedorId
        ]);
    }

    public function update(Produto $produto): bool
    {
        // Adiciona fornecedor_id ao UPDATE
        $sql = "UPDATE produtos 
                SET nome = :nome, preco = :preco, ativo = :ativo, 
                    dataDeCadastro = :dataDeCadastro, dataDeValidade = :dataDeValidade,
                    fornecedor_id = :fornecedor_id
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $fornecedorId = $produto->getFornecedor() ? $produto->getFornecedor()->getId() : null;
        
        return $stmt->execute([
            ':id' => $produto->getId(),
            ':nome' => $produto->getNome(),
            ':preco' => $produto->getPreco(),
            ':ativo' => $produto->getAtivo() ? 1 : 0,
            ':dataDeCadastro' => $produto->getDataDeCadastro(),
            ':dataDeValidade' => $produto->getDataDeValidade(),
            ':fornecedor_id' => $fornecedorId
        ]);
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM produtos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
