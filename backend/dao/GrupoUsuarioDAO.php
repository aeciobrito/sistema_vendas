<?php

require_once 'backend/config/Database.php';
require_once 'backend/entity/Usuario.php';
require_once 'backend/entity/Permissao.php';
require_once 'BaseDAO.php';

class GrupoUsuarioDAO implements BaseDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        try {
            $sql = "SELECT * FROM GrupoUsuario WHERE Id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result) {
                return new GrupoUsuario(
                    $result['Id'],
                    $result['Nome'],
                    $result['Descricao'],
                    $result['DataCriacao'],
                    $result['DataAtualizacao'],
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
            $sql = "SELECT * FROM GrupoUsuario WHERE";
            $stmt = $this->db->prepare($sql);
            $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(function ($grupo) {
                return new GrupoUsuario(
                    $grupo['Id'],
                    $grupo['Nome'],
                    $grupo['Descricao'],
                    $grupo['DataCriacao'],
                    $grupo['DataAtualizacao'],
                    $grupo['Ativo']
                );
            }, $grupos);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
    
    public function getGrupoUsuarioWithPermissions($grupoUsuarioID) {
        $sql = "SELECT 
                    g.Id AS g_id,
                    g.Nome AS g_nome,
                    g.Descricao AS g_descricao,
                    g.DataCriacao AS g_dataCriacao,
                    g.DataAtualizacao AS g_dataAtualizacao,
                    g.UsuarioAtualizacao AS g_usuarioAtualizacao,
                    g.Ativo AS g_ativo,
                    p.Id AS p_id,
                    p.Nome AS p_nome,
                    p.Descricao AS p_descricao,
                    p.DataCriacao AS p_dataCriacao,
                    p.DataAtualizacao AS p_dataAtualizacao,
                    p.UsuarioAtualizacao AS p_usuarioAtualizacao,
                    p.Ativo AS p_ativo
                FROM GrupoUsuario g
                JOIN PermissaoGrupo pg ON g.Id = pg.GrupoUsuarioID
                JOIN Permissao p ON pg.PermissaoID = p.Id
                WHERE g.Id = :grupoUsuarioID";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':grupoUsuarioID', $grupoUsuarioID);
        $stmt->execute();

        $grupoUsuario = null;
        $permissoes = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if($grupoUsuario === null) {
                $grupoUsuario = new GrupoUsuario(
                    $row['g_id'],
                    $row['g_nome'],
                    $row['g_descricao'],
                    $row['g_dataCriacao'],
                    $row['g_dataAtualizacao'],
                    $row['p_ativo']
                );
            }

            $permissoes[] = new Permissao(
                $row['p_id'],
                $row['p_nome'],
                $row['p_descricao'],
                $row['p_dataCriacao'],
                $row['p_dataAtualizacao'],
                $row['p_usuarioAtualizacao'],
                $row['p_ativo']
            );
        }

        if($grupoUsuario !== null) {
            $grupoUsuario->setPermissoes($permissoes);
        }

        return $grupoUsuario;
    }

    public function create($grupoUsuario) {
        try {
            $sql = "INSERT INTO GrupoUsuario (Nome, Descricao, DataCriacao, DataAtualizacao, UsuarioAtualizacao, Ativo)
                    VALUES (:nome, :descricao, :dataCriacao, :dataAtualizacao, :usuarioAtualizacao, :ativo)";
            
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nome' => $grupoUsuario->getNome(),
                ':descricao' => $grupoUsuario->getDescricao(),
                ':dataCriacao' => $grupoUsuario->getDataCriacao(),
                ':dataAtualizacao' => $grupoUsuario->getDataAtualizacao(),
                ':usuarioAtualizacao' => $grupoUsuario->getUsuarioAtualizacao(),
                ':ativo' => $grupoUsuario->getAtivo()
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($grupoUsuario) {
        try {
            $sql = "UPDATE GrupoUsuario 
                    SET Nome = :nome, Descricao = :descricao, DataCriacao = :dataCriacao, 
                        DataAtualizacao = :dataAtualizacao, UsuarioAtualizacao = :usuarioAtualizacao, 
                        Ativo = :ativo 
                    WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
    
            return $stmt->execute([
                ':id' => $grupoUsuario->getId(),
                ':nome' => $grupoUsuario->getNome(),
                ':descricao' => $grupoUsuario->getDescricao(),
                ':dataCriacao' => $grupoUsuario->getDataCriacao(),
                ':dataAtualizacao' => $grupoUsuario->getDataAtualizacao(),
                ':usuarioAtualizacao' => $grupoUsuario->getUsuarioAtualizacao(),
                ':ativo' => $grupoUsuario->getAtivo()
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM GrupoUsuario WHERE Id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getGruposByPermissaoId($permissaoId) {
        try {
            $sql = "SELECT GrupoUsuario.* FROM GrupoUsuario
                    INNER JOIN PermissaoGrupo ON GrupoUsuario.Id = PermissaoGrupo.GrupoUsuarioID
                    WHERE PermissaoGrupo.PermissaoID = :permissaoId";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':permissaoId' => $permissaoId]);

            $grupos = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $grupos[] = new GrupoUsuario(
                    $row['Id'],
                    $row['Nome'],
                    $row['Descricao'],
                    $row['DataCriacao'],
                    $row['DataAtualizacao'],
                    $row['Ativo']
                );
            }
            return $grupos;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return[];
        }
    }
}

?>