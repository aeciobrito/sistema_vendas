<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            
            // Verificação de segurança para garantir que as constantes de configuração foram carregadas
            if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER')) {
                die("Erro Crítico: As constantes de configuração do banco de dados não foram carregadas. Verifique o arquivo config.php e os arquivos config.*.php.");
            }

            // Lê as configurações das constantes que foram definidas pelos arquivos config.*.php
            $db_host = DB_HOST;
            $db_name = DB_NAME;
            $db_user = DB_USER;
            $db_pass = DB_PASS;
            $use_ssl = (defined('DB_USE_SSL') && DB_USE_SSL === 'true');
            
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
                error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
                die("Erro: Não foi possível conectar ao banco de dados. Por favor, tente novamente mais tarde.");
            }
        }
        return self::$instance;
    }
}