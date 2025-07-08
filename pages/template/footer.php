</main>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> Sistema de Vendas. Todos os direitos reservados.</p>
</footer>

<script>
    // Função para atualizar o contador no header
    function atualizarContadorCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        // Soma a quantidade de todos os itens no carrinho
        const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
        
        const contador = document.getElementById('cart-counter');
        if (contador) {
            contador.innerText = totalItens;
            // Mostra ou esconde o contador se for maior que zero
            contador.style.display = totalItens > 0 ? 'block' : 'none';
        }
    }

    // Função para adicionar um item ao carrinho
    function adicionarAoCarrinho(id, nome, preco, imagemUrl) {
        let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
        const itemExistente = carrinho.find(item => item.id === id);
        
        if (itemExistente) {
            itemExistente.quantidade++;
        } else {
            carrinho.push({ id, nome, preco, imagemUrl, quantidade: 1 });
        }
        
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
        alert('"' + nome + '" foi adicionado ao carrinho!');
        
        // Atualiza o contador no header
        atualizarContadorCarrinho();
    }

    // Chama a função para garantir que o contador esteja correto ao carregar a página
    document.addEventListener('DOMContentLoaded', atualizarContadorCarrinho);
</script>
</script>

</body>
</html>