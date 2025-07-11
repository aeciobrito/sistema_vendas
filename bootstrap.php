<?php
/**
 * Ponto de Entrada Único (Bootstrap)
 * Este arquivo prepara todo o ambiente da aplicação.
 * Ele deve ser o PRIMEIRO e ÚNICO arquivo a ser incluído pelas páginas.
 */

// 1. Inicia a sessão de forma segura.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Define o caminho raiz para facilitar os includes.
define('ROOT_PATH', __DIR__);

// 3. Detecta o ambiente e carrega as CONFIGURAÇÕES SENSÍVEIS primeiro.
// Esta é a parte mais crítica: definir as constantes ANTES de qualquer outra coisa.
if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
    // Ambiente de Desenvolvimento (XAMPP)
    define('BASE_URL', 'http://localhost/sistema_vendas');
    require_once ROOT_PATH . '/database/config.local.php';
} else {
    // Ambiente de Produção (InfinityFree ou outro)
    define('BASE_URL', 'https://sisvendasuc7.free.nf'); // Sua URL de produção
    require_once ROOT_PATH . '/database/config.production.php';
}

// 4. Agora que as configurações estão carregadas, carrega as CLASSES e SERVIÇOS.
// A ordem aqui garante que as dependências sejam resolvidas corretamente.

// Classe de Conexão com o Banco
require_once ROOT_PATH . '/database/Database.php';

// Models (as classes base)
require_once ROOT_PATH . '/model/Entidade.php';
require_once ROOT_PATH . '/model/Usuario.php';
require_once ROOT_PATH . '/model/Categoria.php';
require_once ROOT_PATH . '/model/FormaPagamento.php';
require_once ROOT_PATH . '/model/Produto.php';
require_once ROOT_PATH . '/model/ItemPedido.php';
require_once ROOT_PATH . '/model/Pedido.php';

// DAOs (que dependem dos Models e do Database)
require_once ROOT_PATH . '/dao/UsuarioDAO.php';
require_once ROOT_PATH . '/dao/CategoriaDAO.php';
require_once ROOT_PATH . '/dao/FormaPagamentoDAO.php';
require_once ROOT_PATH . '/dao/ProdutoDAO.php';
require_once ROOT_PATH . '/dao/ItemPedidoDAO.php';
require_once ROOT_PATH . '/dao/PedidoDAO.php';

// Serviços (que dependem dos DAOs e Models)
require_once ROOT_PATH . '/services/authService.php';