<?php

require_once '../backend/config/Database.php';
require_once '../backend/entity/Produto.php';
require_once 'BaseDAO.php';

class ProdutoDAO implements BaseDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM Produto WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $produto ? new Produto(
                $produto['Id'], 
                $produto['Nome'], 
                $produto['Descricao'], 
                $produto['Preco'], 
                $produto['CategoriaID'], 
                $produto['DataCriacao'], 
                $produto['DataAtualizacao'], 
                $produto['UsuarioAtualizacao'], 
                $produto['Ativo']
            ) : null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM Produto";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function($produto) {
                return new Produto(
                    $produto['Id'], 
                    $produto['Nome'], 
                    $produto['Descricao'], 
                    $produto['Preco'], 
                    $produto['CategoriaID'], 
                    $produto['DataCriacao'], 
                    $produto['DataAtualizacao'], 
                    $produto['UsuarioAtualizacao'], 
                    $produto['Ativo']
                );
            }, $produtos);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function create($produto) {
        try {
            $sql = "INSERT INTO Produto (Nome, Descricao, Preco, CategoriaID, DataCriacao, DataAtualizacao, UsuarioAtualizacao, Ativo)
                    VALUES (:nome, :descricao, :preco, :categoriaID, current_timestamp(), current_timestamp(), :usuarioAtualizacao, :ativo)";
            $stmt = $this->db->prepare($sql);
           
            $stmt->execute([
                ':nome' => $produto->getNome(); 
                ':descricao' => $produto->getDescricao(); 
                ':preco' => $produto->getPreco(); 
                ':categoriaID' => $produto->getCategoriaID(); 
                ':usuarioAtualizacao' => $produto->getUsuarioAtualizacao(); 
                ':ativo' => $produto->getAtivo(); 
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($produto) {
        try {
            $sql = "UPDATE Produto SET Nome = :nome, Descricao = :descricao, Preco = :preco, CategoriaID = :categoriaID, DataAtualizacao = current_timestamp(), UsuarioAtualizacao = :usuarioAtualizacao, Ativo = :ativo WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':id' =>  $produto->getId();
                ':nome' =>  $produto->getNome();
                ':descricao' =>  $produto->getDescricao();
                ':preco' =>  $produto->getPreco();
                ':categoriaID' =>  $produto->getCategoriaID();
                ':usuarioAtualizacao' =>  $produto->getUsuarioAtualizacao();
                ':ativo' =>  $produto->getAtivo();
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM Produto WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
