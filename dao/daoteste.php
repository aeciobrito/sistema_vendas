<?php

// Define um cabeçalho para formatar a saída no navegador
header('Content-Type: text/plain; charset=utf-8');

// =================================================================
// 1. SETUP: INCLUIR TODOS OS ARQUIVOS E PREPARAR O AMBIENTE
// =================================================================
echo "===============================================\n";
echo "INICIANDO TESTE DA CAMADA DE ACESSO A DADOS (DAO)\n";
echo "===============================================\n\n";

// Inclui a conexão com o banco e todas as classes
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../model/Entidade.php';
// Models
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../model/Categoria.php';
require_once __DIR__ . '/../model/FormaPagamento.php';
require_once __DIR__ . '/../model/Produto.php';
require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../model/Pedido.php';
// DAOs
require_once __DIR__ . '/UsuarioDAO.php';
require_once __DIR__ . '/CategoriaDAO.php';
require_once __DIR__ . '/FormaPagamentoDAO.php';
require_once __DIR__ . '/ProdutoDAO.php';
require_once __DIR__ . '/ItemPedidoDAO.php';
require_once __DIR__ . '/PedidoDAO.php';

// ATENÇÃO: Descomente as linhas abaixo se quiser limpar as tabelas antes de cada execução.
// Útil para garantir que o teste comece sempre do zero.
/*
echo "LIMPANDO TABELAS...\n";
$db = Database::getInstance();
$db->exec('SET FOREIGN_KEY_CHECKS = 0');
$db->exec('TRUNCATE TABLE item_pedido');
$db->exec('TRUNCATE TABLE pedido');
$db->exec('TRUNCATE TABLE produto');
$db->exec('TRUNCATE TABLE forma_pagamento');
$db->exec('TRUNCATE TABLE categoria');
$db->exec('TRUNCATE TABLE usuario');
$db->exec('SET FOREIGN_KEY_CHECKS = 1');
echo "Tabelas limpas.\n\n";
*/

// Instancia todos os DAOs que serão utilizados
$usuarioDAO = new UsuarioDAO();
$categoriaDAO = new CategoriaDAO();
$formaPagamentoDAO = new FormaPagamentoDAO();
$produtoDAO = new ProdutoDAO();
$pedidoDAO = new PedidoDAO();

try {
    // =================================================================
    // 2. TESTE DO USUARIODAO
    // =================================================================
    echo "-----------------------------------------------\n";
    echo "TESTANDO UsuarioDAO...\n";
    echo "-----------------------------------------------\n";

    echo "CRIANDO: Usuário Admin e Cliente João...\n";
    $adminModel = new Usuario(null, 'Admin do Sistema', 'admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin@sistema.com', null, null, true);
    // Para criar o primeiro usuário, não há um 'adminId', então podemos usar 1 ou criar uma lógica específica.
    $usuarioDAO->create($adminModel, 1);
    
    // Agora que o admin existe, podemos buscá-lo para usar seu ID
    $adminUser = $usuarioDAO->getByUsername('admin');
    $adminId = $adminUser->getId();

    $clienteModel = new Usuario(null, 'João da Silva', 'joao.silva', password_hash('joao123', PASSWORD_DEFAULT), 'joao@exemplo.com', '11987654321', '111.222.333-44');
    $usuarioDAO->create($clienteModel, $adminId);
    echo "-> SUCESSO!\n";

    echo "BUSCANDO: Cliente 'joao.silva'...\n";
    $clienteJoao = $usuarioDAO->getByUsername('joao.silva');
    echo "-> ENCONTRADO: " . $clienteJoao->getNomeCompleto() . " (ID: " . $clienteJoao->getId() . ")\n";

    echo "ATUALIZANDO: Nome do Cliente João...\n";
    $clienteJoaoAtualizado = new Usuario($clienteJoao->getId(), 'João da Silva Santos', 'joao.silva', '', 'joao.santos@exemplo.com', '11987654321', '111.222.333-44');
    $usuarioDAO->update($clienteJoaoAtualizado, $adminId);
    $clienteJoaoVerificacao = $usuarioDAO->getById($clienteJoao->getId());
    echo "-> VERIFICAÇÃO: Nome agora é '" . $clienteJoaoVerificacao->getNomeCompleto() . "'\n";


    // =================================================================
    // 3. TESTE DE CATEGORIA E FORMA DE PAGAMENTO
    // =================================================================
    echo "\n-----------------------------------------------\n";
    echo "TESTANDO CategoriaDAO e FormaPagamentoDAO...\n";
    echo "-----------------------------------------------\n";
    
    echo "CRIANDO: Categorias e Formas de Pagamento...\n";
    $categoriaDAO->create(new Categoria(null, 'Eletrônicos', 'Dispositivos eletrônicos em geral'), $adminId);
    $categoriaDAO->create(new Categoria(null, 'Livros', 'Livros, e-books e audiobooks'), $adminId);
    $formaPagamentoDAO->create(new FormaPagamento(null, 'Cartão de Crédito', 'Pagamento via Visa/Mastercard'), $adminId);
    $formaPagamentoDAO->create(new FormaPagamento(null, 'PIX', 'Pagamento instantâneo'), $adminId);
    echo "-> SUCESSO!\n";

    echo "DESATIVANDO (Soft Delete): Categoria 'Livros'...\n";
    $catLivros = $categoriaDAO->getAll()[1]; // Pega a segunda categoria criada
    $categoriaDAO->softDelete($catLivros->getId(), $adminId);
    $categoriasAtivas = $categoriaDAO->getAll(true);
    echo "-> VERIFICAÇÃO: Número de categorias ativas: " . count($categoriasAtivas) . " (Esperado: 1)\n";


    // =================================================================
    // 4. TESTE DO PRODUTODAO
    // =================================================================
    echo "\n-----------------------------------------------\n";
    echo "TESTANDO ProdutoDAO...\n";
    echo "-----------------------------------------------\n";
    
    echo "CRIANDO: Produtos na categoria 'Eletrônicos'...\n";
    $catEletronicos = $categoriasAtivas[0]; // Pega a categoria 'Eletrônicos' que sobrou
    $produtoNotebook = new Produto(null, 'Notebook Pro X1', 'Notebook de alta performance', 7500.50, $catEletronicos);
    $produtoMouse = new Produto(null, 'Mouse Gamer Z2', 'Mouse óptico sem fio', 250.00, $catEletronicos);
    $produtoDAO->create($produtoNotebook, $adminId);
    $produtoDAO->create($produtoMouse, $adminId);
    echo "-> SUCESSO!\n";

    echo "BUSCANDO: Todos os produtos ativos...\n";
    $produtosAtivos = $produtoDAO->getAll(true);
    $primeiroProduto = $produtosAtivos[0];
    echo "-> ENCONTRADO: '" . $primeiroProduto->getNome() . "' na categoria '" . $primeiroProduto->getCategoria()->getNome() . "'\n";

    
    // =================================================================
    // 5. TESTE DO PEDIDODAO (O GRANDE FINAL)
    // =================================================================
    echo "\n-----------------------------------------------\n";
    echo "TESTANDO PedidoDAO (com transação)...\n";
    echo "-----------------------------------------------\n";
    
    echo "MONTANDO: Um pedido para o cliente João...\n";
    // Busca os dados mais recentes do banco
    $clienteFinal = $usuarioDAO->getById($clienteJoaoVerificacao->getId());
    $notebookFinal = $produtoDAO->getAll(true)[0];
    $mouseFinal = $produtoDAO->getAll(true)[1];
    $formaPagamentoFinal = $formaPagamentoDAO->getAll(true)[0];

    // Cria os models de ItemPedido (sem salvar no banco ainda)
    $item1 = new ItemPedido(null, 0, $notebookFinal, 1, $notebookFinal->getPreco());
    $item2 = new ItemPedido(null, 0, $mouseFinal, 2, $mouseFinal->getPreco());
    
    // Cria o model do Pedido, agregando tudo
    $pedidoModel = new Pedido(
        null, 
        $clienteFinal, 
        date('Y-m-d H:i:s'), 
        $formaPagamentoFinal, 
        'Pendente', 
        true, 
        [$item1, $item2]
    );

    echo "EXECUTANDO: pedidoDAO->create()...\n";
    $sucessoPedido = $pedidoDAO->create($pedidoModel);
    if ($sucessoPedido) {
        echo "-> SUCESSO! Pedido e seus itens foram criados na mesma transação.\n";
    } else {
        echo "-> FALHA! A transação foi revertida (rollBack).\n";
        exit;
    }

    echo "BUSCANDO: O pedido recém-criado para verificação completa...\n";
    $pedidos = $pedidoDAO->getAll(true);
    $pedidoSalvo = $pedidos[0];
    
    echo "\n--- RESUMO DO PEDIDO " . $pedidoSalvo->getId() . " ---\n";
    echo "Cliente: " . $pedidoSalvo->getCliente()->getNomeCompleto() . "\n";
    echo "Status: " . $pedidoSalvo->getStatus() . "\n";
    echo "Total de Itens: " . count($pedidoSalvo->getItens()) . "\n";
    foreach ($pedidoSalvo->getItens() as $item) {
        echo "  - Item: " . $item->getProduto()->getNome() . " | Qtd: " . $item->getQuantidade() . "\n";
    }
    echo "Valor Total: R$ " . number_format($pedidoSalvo->getTotal(), 2, ',', '.') . "\n";
    echo "-------------------------\n";

    echo "\nATUALIZANDO: Status do pedido para 'Pago'...\n";
    $pedidoDAO->updateStatus($pedidoSalvo->getId(), 'Pago', $adminId);
    $pedidoPago = $pedidoDAO->getById($pedidoSalvo->getId());
    echo "-> VERIFICAÇÃO: Status agora é '" . $pedidoPago->getStatus() . "'\n";

    echo "\n===============================================\n";
    echo "TESTES DA CAMADA DAO CONCLUÍDOS COM SUCESSO!\n";
    echo "===============================================\n";

} catch (PDOException $e) {
    echo "\nERRO DE BANCO DE DADOS: " . $e->getMessage();
} catch (Exception $e) {
    echo "\nERRO GERAL: " . $e->getMessage();
}

?>