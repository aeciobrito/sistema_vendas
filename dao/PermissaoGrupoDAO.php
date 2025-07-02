<?php

class PermissaoGrupoDAO extends BaseDAO
{
    public function assignPermissionToGroup(int $permissaoId, int $grupoId): bool
    {
        try {
            $sql = "INSERT INTO PermissaoGrupo (PermissaoID, GrupoUsuarioID) VALUES (:permissao_id, :grupo_id)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':permissao_id' => $permissaoId, ':grupo_id' => $grupoId]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { return true; }
            return false;
        }
    }

    public function removePermissionFromGroup(int $permissaoId, int $grupoId): bool
    {
        $sql = "DELETE FROM PermissaoGrupo WHERE PermissaoID = :permissao_id AND GrupoUsuarioID = :grupo_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':permissao_id' => $permissaoId, ':grupo_id' => $grupoId]);
    }

    public function getPermissionsByGroupId(int $grupoId): array
    {
        $sql = "SELECT p.* FROM Permissao p JOIN PermissaoGrupo pg ON p.Id = pg.PermissaoID WHERE pg.GrupoUsuarioID = :grupo_id AND p.Ativo = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':grupo_id' => $grupoId]);
        
        $permissoes = [];
        foreach ($stmt->fetchAll() as $row) {
            $permissoes[] = new Permissao(
                $row['Id'], $row['Nome'], $row['Descricao'], (bool)$row['Ativo'],
                $row['DataCriacao'], $row['DataAtualizacao'], $row['UsuarioAtualizacao']
            );
        }
        return $permissoes;
    }
}