<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('ROOT_PATH', __DIR__);

if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
    define('BASE_URL', 'http://localhost/sistema_vendas');
    require_once ROOT_PATH . '/database/config.local.php';
} else {
    define('BASE_URL', 'https://sisvendasuc7.free.nf'); // Sua URL de produção
    require_once ROOT_PATH . '/database/config.production.php';
}

require_once ROOT_PATH . '/database/Database.php';

require_once ROOT_PATH . '/model/Entidade.php';
require_once ROOT_PATH . '/model/Usuario.php';
require_once ROOT_PATH . '/model/Categoria.php';
require_once ROOT_PATH . '/model/FormaPagamento.php';
require_once ROOT_PATH . '/model/Produto.php';
require_once ROOT_PATH . '/model/ItemPedido.php';
require_once ROOT_PATH . '/model/Pedido.php';

require_once ROOT_PATH . '/dao/UsuarioDAO.php';
require_once ROOT_PATH . '/dao/CategoriaDAO.php';
require_once ROOT_PATH . '/dao/FormaPagamentoDAO.php';
require_once ROOT_PATH . '/dao/ProdutoDAO.php';
require_once ROOT_PATH . '/dao/ItemPedidoDAO.php';
require_once ROOT_PATH . '/dao/PedidoDAO.php';

require_once ROOT_PATH . '/services/authService.php';