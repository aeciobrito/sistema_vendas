```
/projeto/
├── index.php                   ← Home pública
├── login.php                   ← Login de usuário
├── cadastro.php                ← Cadastro de usuário
├── logout.php                  ← Logout
│
├── produtos/
│   ├── listar.php              ← Público (GET ALL)
│   ├── ver.php                 ← Público (GET BY ID)
│   ├── criar.php               ← Autenticado
│   ├── editar.php              ← Autenticado
│   └── excluir.php             ← Autenticado
│
├── core/
│   ├── authService.php         ← Lógica de autenticação (sessão/token)
│   └── Database.php            ← Singleton PDO
│
├── model/
│   ├── Usuario.php
│   └── Produto.php
│
└── dao/
    ├── UsuarioDAO.php
    └── ProdutoDAO.php
