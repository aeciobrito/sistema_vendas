<?php
require_once __DIR__ . '/../template/header.php';
$pedido_id = $_GET['pedido_id'] ?? 'Não identificado';
?>

<style>
    .success-container {
        text-align: center;
        padding: 4rem 2rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 2rem auto;
    }
    .success-icon {
        font-size: 5rem;
        color: #28a745; /* Verde de sucesso */
    }
    .success-container h1 {
        color: #28a745;
        margin: 1rem 0;
    }
    .success-container p {
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
    .redirect-message {
        margin-top: 2rem;
        font-style: italic;
        color: #6c757d;
    }
</style>

<div class="success-container">
    <div class="success-icon">✓</div>
    <h1>Pedido Realizado com Sucesso!</h1>
    <p>Obrigado pela sua compra.</p>
    <p>O número do seu pedido é: <strong><?= htmlspecialchars($pedido_id) ?></strong></p>
    
    <div class="redirect-message">
        <p>Você será redirecionado para a página inicial em <span id="countdown">3</span> segundos...</p>
    </div>
    
    <br>
    <a href="/sistema_vendas/index.php" class="btn btn-primary">Voltar à Loja Agora</a>
</div>

<script>
    // Executa o script quando o conteúdo da página estiver totalmente carregado
    document.addEventListener('DOMContentLoaded', () => {
        
        // 1. Limpa o carrinho do localStorage, se o parâmetro clear_cart existir na URL
        <?php if (isset($_GET['clear_cart'])): ?>
            localStorage.removeItem('carrinho');
            
            // Verifica se a função do footer existe para atualizar o contador no header
            if (typeof atualizarContadorCarrinho === 'function') {
                atualizarContadorCarrinho();
            }
        <?php endif; ?>

        // 2. Lógica de contagem regressiva e redirecionamento
        const countdownElement = document.getElementById('countdown');
        let seconds = 5;

        const countdownInterval = setInterval(() => {
            seconds--;
            if (countdownElement) {
                countdownElement.innerText = seconds;
            }
            if (seconds <= 0) {
                clearInterval(countdownInterval); // Para o contador
                window.location.href = '/sistema_vendas/index.php'; // Redireciona
            }
        }, 1000); // Executa a cada 1 segundo (1000 ms)
    });
</script>

<?php
// O footer é incluído no final para garantir que o script `atualizarContadorCarrinho` esteja disponível
require_once __DIR__ . '/../template/footer.php';
?>