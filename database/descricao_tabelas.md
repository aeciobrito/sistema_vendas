# Descrição Funcional das Tabelas

## Aqui está uma explicação simples e direta sobre o papel de cada tabela no seu novo sistema de vendas.

**Tabela**: categoria

    Função: Organizar os produtos em grupos.

    O que ela faz: Esta tabela funciona como as seções de uma loja. Cada produto pertence a uma categoria (ex: "Eletrônicos", "Roupas", "Livros"), o que facilita a navegação e a busca por itens relacionados.

**Tabela**: forma_pagamento

    Função: Listar os métodos de pagamento aceitos.

    O que ela faz: Armazena as opções que um cliente pode escolher para pagar por um pedido, como "Cartão de Crédito", "Boleto Bancário", "PIX", etc.

**Tabela**: produto

    Função: É o catálogo de itens à venda.

    O que ela faz: Contém todas as informações sobre cada item que pode ser vendido, incluindo seu nome, descrição, preço e a qual categoria ele pertence.

**Tabela**: usuario

    Função: O coração do sistema, registrando todas as pessoas.

    O que ela faz: Esta é a tabela principal para qualquer pessoa que interage com o sistema. Ela unifica clientes e administradores em um só lugar. O campo is_admin é usado para diferenciar os dois tipos:

        Cliente (is_admin = 0): Pode se cadastrar, fazer login, e realizar compras.

        Administrador (is_admin = 1): Pode gerenciar o sistema (cadastrar produtos, ver todos os pedidos, etc.).

**Tabela**: pedido

    Função: Representa uma única transação de compra.

    O que ela faz: Cada vez que um usuario (cliente) finaliza uma compra, um registro é criado aqui. Esta tabela guarda informações essenciais sobre a venda: quem comprou (cliente_id), quando a compra foi feita (data_pedido), como foi paga (forma_pagamento_id) e o estado atual da compra (status, ex: "Processando", "Enviado", "Entregue").

**Tabela**: item_pedido

    Função: Detalha os produtos dentro de um pedido.

    O que ela faz: Funciona como a "lista de compras" de um pedido específico. Para cada pedido, esta tabela armazena múltiplos registros, um para cada produto diferente que foi comprado, incluindo a quantidade e o preco_unitario no momento da compra. Ela é diretamente ligada à tabela pedido.