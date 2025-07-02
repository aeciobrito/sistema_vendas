<?php
class GrupoUsuario {    
    private $id;
    private $nome;
    private $descricao;    
    private $dataCriacao;
    private $dataAtualizacao;
    private $usuarioAtualizacao;
    private $ativo;

    private $permissoes = [];

    public function __construct($id, $nome, $descricao, $dataCriacao, $dataAtualizacao, $ativo = 1) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->usuarioAtualizacao = null; 
        $this->ativo = $ativo;
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao() {
        return $this->dataAtualizacao;
    }

    public function getUsuarioAtualizacao() {
        return $this->usuarioAtualizacao;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function getPermissoes() {
        return $this->permissoes;
    }

    public function setPermissoes($permissoes) {
        $this->permissoes = $permissoes;
    }

    public function __toString()
    {
        $info = "GrupoUsuarioID: $this->id, Nome: $this->nome, Descricao: $this->descricao";

        if(!empty($this->permissoes)) {
            $info .= "\nPermissões: ";
            foreach ($this->permissoes as $permissao) {
                $info .= "\n - " . $permissao;
            }
        }

        return $info;   
    }
}

?>