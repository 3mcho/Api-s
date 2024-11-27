<?php 
require_once('../includes/Client.Class.php');

// Cambiar la condición para permitir solicitudes GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && isset($_GET['email'])) {

    $id = $_GET['id'];
    $email = $_GET['email'];

    // Verificar si el cliente ya existe
    if (Client::client_validation_in_clientes($id, $email) === "E001") {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'E001']);

    } else {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'A102']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Faltan parametros necesarios.']);
}
?>