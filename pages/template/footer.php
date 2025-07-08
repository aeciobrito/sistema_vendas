</main>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> Sistema de Vendas. Todos os direitos reservados.</p>
</footer>

<script>
function adicionarAoCarrinho(id, nome, preco, imagemUrl) {
    // Busca o carrinho atual do localStorage ou cria um array vazio
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    
    // Verifica se o produto já existe no carrinho
    const itemExistente = carrinho.find(item => item.id === id);
    
    if (itemExistente) {
        // Se existe, apenas incrementa a quantidade
        itemExistente.quantidade++;
    } else {
        // Se não existe, adiciona o novo item
        carrinho.push({
            id: id,
            nome: nome,
            preco: preco,
            imagemUrl: imagemUrl,
            quantidade: 1
        });
    }
    
    // Salva o carrinho atualizado de volta no localStorage
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    
    // Fornece um feedback visual ao usuário
    alert('"' + nome + '" foi adicionado ao carrinho!');
    
    // Opcional: Atualizar um contador de itens no cabeçalho
    // (Implementação futura)
}
</script>

</body>
</html>