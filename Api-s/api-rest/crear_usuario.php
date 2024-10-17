<?php 
require_once('../includes/Client.Class.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['nombre_usuario']) && isset($_GET['correo_electronico']) && isset($_GET['contraseña']) && isset($_GET['tipo_plan']) && isset($_GET['activo']) ){
        Client:: crear_usuario($_GET['nombre_usuario'],$_GET['correo_electronico'],$_GET['contraseña'],$_GET['tipo_plan'],$_GET['activo']);
    }else{
   
    }

?>