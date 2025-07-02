-- GrupoUsuario Padrão
INSERT INTO GrupoUsuario (Nome, Descricao) VALUES
	('Sistema', 'Responsáveis Pelo Sistema'),
    ('Administrador', 'Gerenciador de Perfis e Recursos do Sistema'),
    ('Generico', 'Grupo de usuários padrão do sistema');

-- Usuário Padrão
INSERT INTO Usuario
(`NomeUsuario`,`Senha`,`Email`,`GrupoUsuarioID`,`Ativo`,`DataCriacao`,`DataAtualizacao`,`UsuarioAtualizacao`) VALUES
('MasterUser','System@123','system@mail.com', 1,1, current_timestamp(), current_timestamp(),null);

-- Permissões padrão do Sistema
INSERT INTO Permissao (Nome, Descricao) VALUES
	('Gerenciar Usuários', 'Permissão de administrar os papéis de um usuário'),
    ('Visualizar Produtos', 'Permissão de visualizar todos os produtos'),
    ('Gerenciar Pedidos', 'Permissão de alterar os status de um pedido');

-- Mapeamento inicial de permissões por grupo
INSERT INTO PermissaoGrupo (PermissaoID, GrupoUsuarioID) VALUES
	(1, 1), (1, 2),
    (2, 1), (2, 2), (2, 3),
    (3, 2);
    