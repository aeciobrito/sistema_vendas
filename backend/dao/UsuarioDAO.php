<?php

require_once 'backend/config/Database.php';
require_once 'backend/entity/Usuario.php';
require_once 'backend/entity/GrupoUsuario.php';
require_once 'BaseDAO.php';

class UsuarioDAO implements BaseDAO {
    private $db;
    private $grupoUsuarioGenericoId = 3;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM Usuario WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([':id' => $id]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ? new Usuario(
                $usuario['Id'],
                $usuario['NomeUsuario'],
                $usuario['Senha'],
                $usuario['Email'],
                $usuario['GrupoUsuarioID'],
                $usuario['Ativo'],
                $usuario['DataCriacao'],
                $usuario['DataAtualizacao']                
            ) : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getUsuarioWithGrupo($usuarioID) {
        $sql = "SELECT 
                    u.Id AS u_id,
                    u.NomeUsuario AS u_nomeUsuario,
                    u.Senha AS u_senha,
                    u.Email AS u_email,
                    u.GrupoUsuarioID AS u_grupoUsuarioID,
                    u.Ativo AS u_ativo,
                    u.DataCriacao AS u_dataCriacao,
                    u.DataAtualizacao AS u_dataAtualizacao,
                    u.UsuarioAtualizacao AS u_usuarioAtualizacao,
                    g.Id AS g_id,
                    g.Nome AS g_nome,
                    g.Descricao AS g_descricao,
                    g.DataCriacao AS g_dataCriacao,
                    g.DataAtualizacao AS g_dataAtualizacao,
                    g.UsuarioAtualizacao AS g_usuarioAtualizacao,
                    g.Ativo AS g_ativo
                FROM Usuario u
                JOIN GrupoUsuario g ON u.GrupoUsuarioID = g.Id
                WHERE u.Id = :usuarioID";
        
        $stmt = $this->db->prepare($sql);

        $stmt->execute([':usuarioID' => $usuarioID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $grupoUsuario = new GrupoUsuario(
            $row['g_id'],
            $row['g_nome'],
            $row['g_descricao'],
            $row['g_dataCriacao'],
            $row['g_dataAtualizacao'],
            $row['g_ativo']
        );

        $usuario = new Usuario(
            $row['u_id'],
            $row['u_nomeUsuario'],
            $row['u_senha'],
            $row['u_email'],
            $row['u_grupoUsuarioID'],
            $row['u_ativo'],
            $row['u_dataCriacao'],
            $row['u_dataAtualizacao']                
        );

        $usuario->setGrupoUsuario($grupoUsuario);

        return $usuario;
    }


    public function getAll() {
        try {
            $sql = "SELECT * FROM Usuario";
    
            $stmt = $this->db->prepare($sql);
    
            $stmt->execute();
    
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return array_map(function ($usuario) {
                return new Usuario(
                    $usuario['Id'],
                    $usuario['NomeUsuario'],
                    $usuario['Senha'],
                    $usuario['Email'],
                    $usuario['GrupoUsuarioID'],
                    $usuario['Ativo'],
                    $usuario['DataCriacao'],
                    $usuario['DataAtualizacao'],
                    $usuario['Token']
                );
            }, $usuarios);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function create($usuario) {
        try {
            $sql = "INSERT INTO Usuario( NomeUsuario , Senha , Email , GrupoUsuarioID , Ativo , DataCriacao , DataAtualizacao , UsuarioAtualizacao, Token)
                    VALUES(:nomeUsuario, :senha, :email, :grupoUsuarioID, :ativo, current_timestamp(),current_timestamp(),null, :token)";

            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':nomeUsuario' => $usuario->getNomeUsuario(),
                ':senha' => $usuario->getSenha(),
                ':email' => $usuario->getEmail(),
                ':grupoUsuarioID' => this->grupoUsuarioGenericoId,
                ':ativo' => $usuario->getAtivo(),
                ':token' => $usuario->getToken()
            ]);

            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($usuario) {
        try {
            $existingUser = $this->getById($usuario->getId());

            if(!$existingUser) {
            }

            $sql = "UPDATE Usuario SET NomeUsuario = :nomeUsuario, Senha = :senha, Email = :email,
            GrupoUsuarioID = :grupoUsuarioID, Ativo = :ativo, DataAtualizacao = current_timestamp(), Token = :token
            WHERE Id = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':id' => $usuario->getId(),
                ':nomeUsuario' => $usuario->getNomeUsuario(),
                ':senha' => $usuario->getSenha(),
                ':email' => $usuario->getEmail(),
                ':grupoUsuarioID' => $usuario->getGrupoUsuarioId(),
                ':ativo' => $usuario->getAtivo(),
                ':token' => $usuario->getToken()
            ]);

            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM Usuario WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateToken($id, $token) {
        $sql = "UPDATE Usuario SET Token = :token WHERE Id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':token' => $token
        ]);

        return $token;
    }

    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM Usuario WHERE Email = :email";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([':email' => $email]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);            

            return $usuario ? 
                new Usuario($usuario['Id'],
                            $usuario['NomeUsuario'], 
                            $usuario['Senha'], 
                            $usuario['Email'], 
                            $usuario['GrupoUsuarioID'],
                            $usuario['Ativo'],
                            $usuario['DataCriacao'],
                            $usuario['DataAtualizacao'],
                            $usuario['Token']) 
                : null;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}

?>