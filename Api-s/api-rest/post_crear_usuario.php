<?php 
require_once('../includes/Client.Class.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['nombre_usuario']) && isset($_GET['alias']) && isset($_GET['correo_electronico']) && isset($_GET['password']) && isset($_GET['activo']) && isset($_GET['fk_cliente']) ){
        Client:: crear_usuario($_GET['nombre_usuario'],$_GET['alias'],$_GET['correo_electronico'],$_GET['password'],$_GET['activo'], $_GET['fk_cliente']);
    }else{
   
    }

?>