Categoria: Armazena informações sobre as categorias dos produtos disponíveis para venda.
    CategoriaID: Chave primária única para identificar cada categoria.
    Nome: Nome da categoria.
    Descricao: Descrição da categoria.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se a categoria está ativa (1) ou inativa (0).
 
FormaPagamento: Mantém informações sobre as formas de pagamento disponíveis para os clientes.
    FormaPagamentoID: Chave primária única para identificar cada forma de pagamento.
    Nome: Nome da forma de pagamento.
    Descricao: Descrição da forma de pagamento.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se a forma de pagamento está ativa (1) ou inativa (0).
 
Produto: Mantém informações sobre os produtos disponíveis para venda.
    ProdutoID: Chave primária única para identificar cada produto.
    Nome: Nome do produto.
    Descricao: Descrição detalhada do produto.
    Preco: Preço do produto.
    CategoriaID: Chave estrangeira que referencia a tabela de categorias de produtos.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se o produto está ativo (1) ou inativo (0).
 
Cliente: Armazena informações sobre os clientes que realizam compras.
    ClienteID: Chave primária única para identificar cada cliente.
    Nome: Nome do cliente.
    Email: Endereço de e-mail do cliente.
    Telefone: Número de telefone do cliente.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se o cliente está ativo (1) ou inativo (0).
 
Pedido: Mantém informações sobre os pedidos realizados pelos clientes.
    PedidoID: Chave primária única para identificar cada pedido.
    ClienteID: Chave estrangeira que referencia a tabela de clientes.
    DataPedido: Data em que o pedido foi realizado.
    FormaPagamentoID: Chave estrangeira que referencia a tabela de formas de pagamento.
    Status: Status do pedido.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
 
ItemPedido: Mantém informações sobre os itens incluídos em cada pedido.
    ItemPedidoID: Chave primária única para identificar cada item de pedido.
    PedidoID: Chave estrangeira que referencia a tabela de pedidos.
    ProdutoID: Chave estrangeira que referencia a tabela de produtos.
    Quantidade: Quantidade do produto incluída no pedido.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
 
GrupoUsuario: Mantém informações sobre os grupos de usuários do sistema.
    GrupoUsuarioID: Chave primária única para identificar cada grupo de usuário.
    Nome: Nome do grupo de usuário.
    Descricao: Descrição do grupo de usuário.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se o grupo de usuário está ativo (1) ou inativo (0).
 
Permissao: Mantém informações sobre as permissões do sistema.
    PermissaoID: Chave primária única para identificar cada permissão.
    Nome: Nome da permissão.
    Descricao: Descrição da permissão.
    DataCriacao: Data de criação do registro.
    DataAtualizacao: Data da última atualização do registro.
    UsuarioAtualizacao: ID do usuário que realizou a última atualização.
    Ativo: Indica se a permissão está ativa (1) ou inativa (0).
 
PermissaoGrupo: Associa permissões a grupos de usuários.
    PermissaoID: Chave estrangeira que referencia a tabela de permissões.
    GrupoUsuarioID: Chave estrangeira que referencia a tabela de grupos de usuário.
    PRIMARY KEY (PermissaoID, GrupoUsuarioID): Combinação de chaves primárias para garantir a unicidade.
 
Usuario: Armazena informações sobre os usuários do sistema.
 
UsuarioID: Chave primária única para identificar cada usuário.
NomeUsuario: Nome de usuário único para login.
Senha: Senha criptografada do usuário.
Email: Endereço de e-mail do usuário.
GrupoUsuarioID: Chave estrangeira que referencia a tabela de grupos de usuário.
Ativo: Indica se o usuário está ativo (1) ou inativo (0).
DataCriacao: Data de criação do registro.
DataAtualizacao: Data da última atualização do registro.
UsuarioAtualizacao: ID do usuário que realizou a última atualização.