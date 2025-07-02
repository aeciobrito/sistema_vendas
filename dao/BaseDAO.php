<?php

// É assumido que os arquivos de Model e o Database.php estão disponíveis.
// require_once __DIR__ . '/../model/AllModels.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/../model/Cliente.php';
require_once __DIR__ . '/../model/FormaPagamento.php';
require_once __DIR__ . '/../model/GrupoUsuario.php';
require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/Permissao.php';
require_once __DIR__ . '/../model/PermissaoGrupo.php';
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../model/Usuario.php';

class BaseDAO
{
    protected PDO $db;

    public function __construct()
    {
        // O Database::getInstance() deve ser implementado como um Singleton.
        $this->db = Database::getInstance();
    }
}