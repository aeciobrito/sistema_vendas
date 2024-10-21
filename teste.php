<?php

require_once "./backend/dao/UsuarioDAO.php";
require_once "./backend/dao/GrupoUsuarioDAO.php";

$usuarioDAO = new UsuarioDAO();
$grupoUsuarioDAO = new GrupoUsuarioDAO();

$usuario = $usuarioDAO->getById(1);
echo "<pre>";
echo $usuario;
echo "</pre>";

$usuario = $usuarioDAO->getUsuarioWithGrupo(1);
echo "<pre>";
echo $usuario;
echo "</pre>";

$grupoUsuario = $grupoUsuarioDAO->getGrupoUsuarioWithPermissions($usuario->getGrupoUsuario()->getId());
echo "<pre>";
echo $grupoUsuario;
echo "</pre>";

// echo $usuario;


?>