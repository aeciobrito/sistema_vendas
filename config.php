<?php

// Inicia a sessão de forma segura no início de tudo.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- DETECÇÃO AUTOMÁTICA DE AMBIENTE E CONFIGURAÇÃO ---

define('ROOT_PATH', __DIR__);

// Verifica se o servidor é o localhost para definir as configurações corretas.
if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') {
    
    // --- AMBIENTE DE DESENVOLVIMENTO (XAMPP) ---
    
    define('BASE_URL', 'http://localhost/sistema_vendas');
    
    // Carrega as credenciais do banco de dados local
    require_once ROOT_PATH . '/database/config.local.php';

} else {
    
    // --- AMBIENTE DE PRODUÇÃO (InfinityFree ou outro) ---
    
    define('BASE_URL', 'https://sisvendasuc7.free.nf'); // Sua URL de produção
    
    // Carrega as credenciais do banco de dados de produção
    require_once ROOT_PATH . '/database/config.production.php';
}


// --- INCLUDES GLOBAIS ---
// Agora que o ambiente está configurado, podemos incluir o resto.

// Inclui o serviço de autenticação
require_once ROOT_PATH . '/services/authService.php';

// Inclui a classe do Database (que agora usará as variáveis de ambiente corretas)
require_once ROOT_PATH . '/database/Database.php';

?>