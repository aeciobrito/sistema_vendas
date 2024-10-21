<?php
class Usuario {
    private $id;
    private $nomeUsuario;
    private $senha;
    private $email;
    private $grupoUsuarioID;
    private $ativo;
    private $dataCriacao;
    private $dataAtualizacao;
    private $token;

    private $grupoUsuario;
   
    public function __construct($id, $nomeUsuario, $senha, $email, $grupoUsuarioID, $token, $dataCriacao = null, $dataAtualizacao = null) {
        $this->id = $id;
        $this->nomeUsuario = $nomeUsuario;
        $this->senha = $senha;
        $this->email = $email;
        $this->grupoUsuarioID = $grupoUsuarioID;
        $this->token = $token;
        $this->ativo = true;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;        
    }

    public function getId() {
        return $this->id;
    }

    public function getNomeUsuario() {
        return $this->nomeUsuario;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getGrupoUsuarioId() {
        return $this->grupoUsuarioID;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao() {
        return $this->dataAtualizacao;
    }
    
    public function getToken() {
        return $this->token;
    }

    public function getGrupoUsuario() {
        return $this->grupoUsuario;
    }

    public function setGrupoUsuario($grupoUsuario) {
        $this->grupoUsuario = $grupoUsuario;
    }

    public function __toString() {
        $info = "UsuarioID: $this->id, Nome: $this->nomeUsuario, Email: $this->email";

        if($this->grupoUsuario != null) {
            $info = $info . "\n Grupo Usuario: $this->grupoUsuario";
        }
        return $info;
    }
}

?>