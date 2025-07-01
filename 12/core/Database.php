<?php
class Database
{
    public static function getInstance()
    {
        $db_host = "localhost";
        $db_name = "venda";
        $db_user = "root";
        $db_pass = "";

        try {
            $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro de conexão com o banco: " . $e->getMessage());
        }
    }
}
?>