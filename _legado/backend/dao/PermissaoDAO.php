<?php

require_once '../Database.php';
require_once '../entity/Permissao.php';
require_once 'BaseDAO.php';

class PermissaoDAO implements BaseDAO 
{
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM Permissao WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return new Permissao(
                    $result['Id'],
                    $result['Nome'],
                    $result['Descricao'],
                    $result['DataCriacao'],
                    $result['DataAtualizacao'],
                    $result['UsuarioAtualizacao'],
                    $result['Ativo']
                );
            }
            return null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAll() {
        try {
            $sql = "SELECT * FROM Permissao";
            $stmt = $this->db->query($sql);
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC); 

            return array_map(function($permission) {
                return new Permissao(
                    $permission['Id'],
                    $permission['Nome'],
                    $permission['Descricao'],
                    $permission['DataCriacao'],
                    $permission['DataAtualizacao'],
                    $permission['UsuarioAtualizacao'],
                    $permission['Ativo']
                );
            }, $permissions);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function create($permissao) {
        try {
            $sql = "INSERT INTO Permissao (Nome, Descricao, DataCriacao, DataAtualizacao, UsuarioAtualizacao, Ativo) 
                    VALUES (:nome, :descricao, :dataCriacao, :dataAtualizacao, :usuarioAtualizacao, :ativo)";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':nome' => $permissao->getNome(), 
                ':descricao' => $permissao->getDescricao(), 
                ':dataCriacao' => $permissao->getDataCriacao(), 
                ':dataAtualizacao' => $permissao->getDataAtualizacao(), 
                ':usuarioAtualizacao' => $permissao->getUsuarioAtualizacao(), 
                ':ativo' => $permissao->getAtivo()
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function update($permissao) {
        try {
            $sql = "UPDATE Permissao 
                    SET Nome = :nome, Descricao = :descricao, DataCriacao = :dataCriacao, 
                        DataAtualizacao = :dataAtualizacao, UsuarioAtualizacao = :usuarioAtualizacao, 
                        Ativo = :ativo 
                    WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
    
            return $stmt->execute([
                ':id' => $permissao->getId(), 
                ':nome' => $permissao->getNome(), 
                ':descricao' => $permissao->getDescricao(), 
                ':dataCriacao' => $permissao->getDataCriacao(), 
                ':dataAtualizacao' => $permissao->getDataAtualizacao(), 
                ':usuarioAtualizacao' => $permissao->getUsuarioAtualizacao(), 
                ':ativo' => $permissao->getAtivo()
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM Permissao WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}

?>