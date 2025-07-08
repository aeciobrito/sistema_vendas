<?php
class Produto
{
    private $id;
    private $nome;
    private $descricao;
    private $preco;
    private $categoriaID;
    private $dataCriacao;
    private $dataAtualizacao;
    private $usuarioAtualizacao;
    private $ativo;

    public function __construct($id, $nome, $descricao, $preco, $categoriaID, $dataCriacao, $dataAtualizacao, $usuarioAtualizacao, $ativo)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoriaID = $categoriaID;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->usuarioAtualizacao = $usuarioAtualizacao;
        $this->ativo = $ativo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function getCategoriaID()
    {
        return $this->categoriaID;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao()
    {
        return $this->dataAtualizacao;
    }

    public function getUsuarioAtualizacao()
    {
        return $this->usuarioAtualizacao;
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function setCategoriaID($categoriaID)
    {
        $this->categoriaID = $categoriaID;
    }

    public function setUsuarioAtualizacao($usuarioId)
    {
        $this->usuarioAtualizacao = $usuarioId;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function __toString()
    {
        return "ID: $this->id, Nome: $this->nome, Descrição: $this->descricao, Preço: $this->preco, CategoriaID: $this->categoriaID, Data Criação: $this->dataCriacao, Data Atualização: $this->dataAtualizacao, Usuário Atualização: $this->usuarioAtualizacao, Ativo: $this->ativo";
    }
}
