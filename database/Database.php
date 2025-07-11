<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            
            // Lê as configurações que foram definidas pelos arquivos config.*.php
            $db_host = getenv('DB_HOST');
            $db_name = getenv('DB_NAME');
            $db_user = getenv('DB_USER');
            $db_pass = getenv('DB_PASS');
            $use_ssl = getenv('DB_USE_SSL') === 'true';

            // Verificação de segurança para garantir que as configurações foram carregadas
            if ($db_host === false || $db_name === false || $db_user === false) {
                die("Erro Crítico: As variáveis de ambiente do banco de dados não foram carregadas. Verifique o arquivo config.php.");
            }
            
            try {
                // Opções padrão da conexão PDO
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ];

                // Adiciona as opções de SSL se a configuração exigir
                if ($use_ssl) {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
                }

                // Cria a instância do PDO
                self::$instance = new PDO(
                    "mysql:host={$db_host};dbname={$db_name}",
                    $db_user,
                    $db_pass,
                    $options
                );
                
            } catch (PDOException $e) {
                // Em produção, é melhor logar o erro do que exibi-lo na tela.
                error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
                die("Erro: Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
            }
        }
        return self::$instance;
    }
}