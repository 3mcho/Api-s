<?php 
require_once('../includes/Client.Class.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['nombre_completo']) && isset($_GET['direccion']) && isset($_GET['localidad']) && isset($_GET['cp']) && isset($_GET['telefono']) && isset($_GET['correo_electronico']) && isset($_GET['estatus'])){
        Client:: insert_client($_GET['nombre_completo'],$_GET['direccion'],$_GET['localidad'],$_GET['cp'],$_GET['telefono'],$_GET['correo_electronico'],($_GET['estatus']));
    }else{
        
    }

?>