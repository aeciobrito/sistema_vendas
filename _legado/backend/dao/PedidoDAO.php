<?php
require_once '../config/Database.php';
require_once '../entity/Pedido.php';

class PedidoDAO implements BaseDAO {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getById($id) {
        // Implementação pra obter um pedido por ID
    }

    public function getAll() {
        // Implementação pra obter todos os pedidos
    }

    public function create($entity) {
        // Implementação pra criar um pedido novo
    }

    public function update($entity) {
        // Implementação pra criar atualizar um pedido
        // recebendo o id no corpo da requisição
    }

    public function delete($id) {
        // Implementação da remoção de um pedido por id
    }
}
?>