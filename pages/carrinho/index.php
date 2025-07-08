<?php
// O authService já inicia a sessão, então ele deve vir primeiro.
require_once __DIR__ . '/../../services/authService.php';

// Inclui todos os outros arquivos de Model e DAO necessários
require_once __DIR__ . '/../../database/Database.php';
require_once __DIR__ . '/../../model/Entidade.php';
require_once __DIR__ . '/../../model/Usuario.php';
require_once __DIR__ . '/../../dao/UsuarioDAO.php';
require_once __DIR__ . '/../../model/FormaPagamento.php';
require_once __DIR__ . '/../../dao/FormaPagamentoDAO.php';
require_once __DIR__ . '/../../model/Produto.php';
require_once __DIR__ . '/../../dao/ProdutoDAO.php';
require_once __DIR__ . '/../../model/ItemPedido.php';
require_once __DIR__ . '/../../dao/ItemPedidoDAO.php';
require_once __DIR__ . '/../../model/Pedido.php';
require_once __DIR__ . '/../../dao/PedidoDAO.php';

$mensagem_erro = '';

// BLOCO DE PROCESSAMENTO (SÓ EXECUTA EM REQUISIÇÕES POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A proteção de login agora é a primeira coisa a ser feita
    requireLogin();
    
    try {
        $carrinho_json = $_POST['carrinho_data'] ?? '[]';
        $forma_pagamento_id = $_POST['forma_pagamento_id'] ?? 0;
        $itens_carrinho = json_decode($carrinho_json, true);

        if (empty($itens_carrinho) || empty($forma_pagamento_id)) {
            throw new Exception("Dados do pedido inválidos.");
        }
        
        // CORREÇÃO: Pega o usuário da sessão e nada mais.
        $cliente = getLoggedUser();
        if (!$cliente) {
            // Esta verificação é redundante por causa do requireLogin(), mas é uma boa prática de segurança.
            throw new Exception("Sessão inválida. Por favor, faça o login novamente.");
        }

        $formaPagamentoDAO = new FormaPagamentoDAO();
        $formaPagamento = $formaPagamentoDAO->getById($forma_pagamento_id);

        if (!$formaPagamento) {
            throw new Exception("Forma de pagamento não encontrada.");
        }
        
        // O restante da lógica para reidratar os objetos continua igual...
        $produtoDAO = new ProdutoDAO();
        $itensPedido = [];
        foreach ($itens_carrinho as $item) {
            $produto = $produtoDAO->getById($item['id']);
            if ($produto) {
                $itensPedido[] = new ItemPedido(null, 0, $produto, $item['quantidade'], $produto->getPreco());
            }
        }
        
        $pedido = new Pedido(null, $cliente, date('Y-m-d H:i:s'), $formaPagamento, 'Pendente', true, $itensPedido);
        
        $pedidoDAO = new PedidoDAO();
        $novo_pedido_id = $pedidoDAO->create($pedido);

        if ($novo_pedido_id) {
            header('Location: obrigado.php?pedido_id=' . $novo_pedido_id . '&clear_cart=1');
            exit;
        } else {
            throw new Exception("Falha ao registrar o pedido no banco de dados.");
        }
    } catch (Exception $e) {
        $mensagem_erro = "Erro: " . $e->getMessage();
    }
}

// LÓGICA DE EXIBIÇÃO (SEMPRE EXECUTA)
$formaPagamentoDAO = new FormaPagamentoDAO();
$formasPagamento = $formaPagamentoDAO->getAll(true);

require_once __DIR__ . '/../template/header.php';
?>

<style>
    .cart-item { display: flex; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem; }
    .cart-item img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; margin-right: 1rem; }
    .cart-item-info { flex-grow: 1; }
    .cart-item-actions { display: flex; align-items: center; }
    .cart-item-actions button { min-width: 30px; }
    .cart-item-actions input { width: 50px; text-align: center; margin: 0 0.5rem; border: 1px solid #ccc; padding: 5px;}
    .cart-summary { margin-top: 2rem; padding: 1.5rem; background-color: #f8f9fa; border-radius: 8px; }
    #cart-container { min-height: 100px; }
    .error-message { color: #dc3545; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; }
</style>

<h1>Seu Carrinho de Compras</h1>

<?php if ($mensagem_erro): ?>
    <div class="error-message"><?= htmlspecialchars($mensagem_erro) ?></div>
<?php endif; ?>

<div id="cart-container">
    </div>

<div class="cart-summary">
    <h2>Resumo do Pedido</h2>
    <form id="form-pedido" action="index.php" method="POST">
        <div class="form-group">
            <label for="forma_pagamento_id">Forma de Pagamento:</label>
            <select id="forma_pagamento_id" name="forma_pagamento_id" required>
                <?php foreach ($formasPagamento as $fp): ?>
                    <option value="<?= $fp->getId() ?>"><?= htmlspecialchars($fp->getNome()) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <p>Total: <strong id="cart-total">R$ 0,00</strong></p>
    <br>
    <button id="btn-finalizar" class="btn btn-primary" onclick="finalizarCompra()">Finalizar Compra</button>
</div>


<script>
    const cartContainer = document.getElementById('cart-container');
    const cartTotalElement = document.getElementById('cart-total');
    const btnFinalizar = document.getElementById('btn-finalizar');

    // FUNÇÃO QUE DESENHA OS ITENS NA TELA
    function renderizarCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        cartContainer.innerHTML = ''; // Limpa o conteúdo atual

        if (carrinho.length === 0) {
            cartContainer.innerHTML = '<p>Seu carrinho está vazio.</p>';
            cartTotalElement.innerText = 'R$ 0,00';
            btnFinalizar.disabled = true;
            return;
        }

        btnFinalizar.disabled = false;
        let valorTotal = 0;

        carrinho.forEach(item => {
            const subtotal = item.preco * item.quantidade;
            valorTotal += subtotal;

            const itemHtml = `
                <div class="cart-item">
                    <img src="${item.imagemUrl}" alt="${item.nome}">
                    <div class="cart-item-info">
                        <strong>${item.nome}</strong><br>
                        <span>Preço Unitário: R$ ${item.preco.toFixed(2).replace('.', ',')}</span>
                    </div>
                    <div class="cart-item-actions">
                        <button class="btn btn-secondary" onclick="mudarQuantidade(${item.id}, -1)">-</button>
                        <input type="number" value="${item.quantidade}" readonly>
                        <button class="btn btn-secondary" onclick="mudarQuantidade(${item.id}, 1)">+</button>
                        <button class="btn btn-danger" style="margin-left: 1rem;" onclick="removerItem(${item.id})">Remover</button>
                    </div>
                </div>
            `;
            cartContainer.innerHTML += itemHtml;
        });

        cartTotalElement.innerText = `R$ ${valorTotal.toFixed(2).replace('.', ',')}`;
    }

    // FUNÇÕES DE MANIPULAÇÃO DO CARRINHO
    function mudarQuantidade(itemId, mudanca) {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        const item = carrinho.find(i => i.id === itemId);

        if (item) {
            item.quantidade += mudanca;
            if (item.quantidade <= 0) {
                carrinho = carrinho.filter(i => i.id !== itemId);
            }
        }
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        renderizarCarrinho();
        atualizarContadorCarrinho(); // Função do footer.php
    }

    function removerItem(itemId) {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        carrinho = carrinho.filter(i => i.id !== itemId);
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        renderizarCarrinho();
        atualizarContadorCarrinho(); // Função do footer.php
    }

    // FUNÇÃO PARA SUBMETER OS DADOS PARA O PHP
    function finalizarCompra() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        if (carrinho.length === 0) {
            alert('Seu carrinho está vazio!');
            return;
        }

        const form = document.getElementById('form-pedido');
        
        // Remove um input antigo se ele existir, para evitar duplicação
        const inputAntigo = form.querySelector('input[name="carrinho_data"]');
        if (inputAntigo) {
            inputAntigo.remove();
        }

        const inputCarrinho = document.createElement('input');
        inputCarrinho.type = 'hidden';
        inputCarrinho.name = 'carrinho_data';
        inputCarrinho.value = JSON.stringify(carrinho);
        
        form.appendChild(inputCarrinho);
        form.submit();
    }
    
    // RENDERIZA O CARRINHO QUANDO A PÁGINA É CARREGADA
    document.addEventListener('DOMContentLoaded', renderizarCarrinho);
</script>

<?php
require_once __DIR__ . '/../template/footer.php';
?>