CREATE DATABASE IF NOT EXISTS venda;
USE venda;

CREATE TABLE IF NOT EXISTS produtos (
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT 1,
    dataDeCadastro DATE NOT NULL,
    dataDeValidade DATE
);

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeUsuario VARCHAR(50) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    token VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cnpj VARCHAR(20) UNIQUE,
    contato VARCHAR(100)
);

ALTER TABLE produtos
ADD COLUMN fornecedor_id INT NULL,
ADD CONSTRAINT fk_produto_fornecedor
    FOREIGN KEY (fornecedor_id) 
    REFERENCES fornecedores(id)
    ON DELETE SET NULL; -- Opcional: se um fornecedor for excluído, o campo no produto fica nulo.
