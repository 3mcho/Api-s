<?php 
require_once('../includes/Client.Class.php');

    if($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['email']) && isset($_GET['new_alias']) && isset($_GET['new_password'])){
        $email = $_GET['email'];
        $new_alias = $_GET['new_alias'];
        $new_password = $_GET['new_password'];

        if (Client::updateUsuariosApp($email, $new_alias, $new_password) === "E001") {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'E001']);

        } else {
            header('Content-Type: application/json');
            echo json_encode(['message' => 'A102']);
        }
    } 
    else {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Faltan parametros necesarios.']);
    }

?>