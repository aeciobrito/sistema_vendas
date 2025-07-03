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


abstract class BaseDAO
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
