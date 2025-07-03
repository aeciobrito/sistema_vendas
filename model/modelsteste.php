<?php

// Define um cabeçalho para formatar a saída no navegador
header('Content-Type: text/plain; charset=utf-8');

// Inclui todas as classes de Modelo necessárias para o teste
require_once __DIR__ . '/Entidade.php';
require_once __DIR__ . '/Categoria.php';
require_once __DIR__ . '/FormaPagamento.php';
require_once __DIR__ . '/Usuario.php';
require_once __DIR__ . '/Produto.php';
require_once __DIR__ . '/ItemPedido.php';
require_once __DIR__ . '/Pedido.php';

echo "===============================================\n";
echo "INICIANDO TESTE DE INSTANCIAÇÃO DE MODELS\n";
echo "===============================================\n\n";

// 1. Criando entidades simples
echo "1. Criando Categoria e Forma de Pagamento...\n";
$categoriaEletronicos = new Categoria(1, 'Eletrônicos', 'Dispositivos eletrônicos e acessórios', true);
$formaPagamentoCartao = new FormaPagamento(1, 'Cartão de Crédito', 'Pagamento via cartão de crédito', true);

print_r($categoriaEletronicos);
print_r($formaPagamentoCartao);
echo "\n";

// 2. Criando um usuário (que será nosso cliente)
echo "2. Criando um Usuário (Cliente)...\n";
$cliente = new Usuario(
    10, 'João da Silva', 'joao.silva', 'senha_super_segura', 'joao@exemplo.com',
    '11999998888', '123.456.789-00', false, true
);

print_r($cliente);
echo "\n";

// 3. Criando produtos que pertencem a uma categoria
echo "3. Criando Produtos...\n";
$produtoNotebook = new Produto(
    101, 'Notebook Gamer', 'Notebook de alta performance para jogos', 7500.50, $categoriaEletronicos, true
);
$produtoMouse = new Produto(
    102, 'Mouse Sem Fio', 'Mouse óptico sem fio com 6 botões', 150.75, $categoriaEletronicos, true
);

print_r($produtoNotebook);
print_r($produtoMouse);
echo "\n";

// 4. Criando itens de um pedido (ainda sem o ID do pedido)
// O ID do pedido (primeiro parâmetro) será associado depois da criação do Pedido.
echo "4. Criando Itens do Pedido...\n";
$item1 = new ItemPedido(null, 0, $produtoNotebook, 1, 7500.50);
$item2 = new ItemPedido(null, 0, $produtoMouse, 2, 149.99); // Preço pode variar no momento da compra

print_r($item1);
print_r($item2);
echo "\n";

// 5. Criando o Pedido completo, associando todas as partes
echo "5. Criando o Pedido completo...\n";
$pedido = new Pedido(
    5001,
    $cliente,
    date('Y-m-d H:i:s'),
    $formaPagamentoCartao,
    'Pendente',
    true,
    [$item1, $item2] // Array de objetos ItemPedido
);

echo "===============================================\n";
echo "RESULTADO FINAL - OBJETO PEDIDO COMPLETO\n";
echo "===============================================\n\n";

// Exibindo o objeto Pedido completo com print_r para ver a estrutura aninhada
print_r($pedido);

echo "\n===============================================\n";
echo "EXIBINDO DADOS COM GETTERS\n";
echo "===============================================\n\n";

echo "ID do Pedido: " . $pedido->getId() . "\n";
echo "Cliente: " . $pedido->getCliente()->getNomeCompleto() . " (Email: " . $pedido->getCliente()->getEmail() . ")\n";
echo "Status: " . $pedido->getStatus() . "\n";
echo "Forma de Pagamento: " . $pedido->getFormaPagamento()->getNome() . "\n";
echo "-----------------------------------------------\n";
echo "Itens do Pedido:\n";

foreach ($pedido->getItens() as $item) {
    echo "  - Produto: " . $item->getProduto()->getNome() . "\n";
    echo "    Categoria: " . $item->getProduto()->getCategoria()->getNome() . "\n";
    echo "    Quantidade: " . $item->getQuantidade() . "\n";
    echo "    Preço Unitário: R$ " . number_format($item->getPrecoUnitario(), 2, ',', '.') . "\n";
    echo "    Subtotal: R$ " . number_format($item->getSubtotal(), 2, ',', '.') . "\n\n";
}

echo "-----------------------------------------------\n";
echo "TOTAL DO PEDIDO: R$ " . number_format($pedido->getTotal(), 2, ',', '.') . "\n";
echo "===============================================\n";
echo "TESTE CONCLUÍDO\n";
echo "===============================================\n";

?>
