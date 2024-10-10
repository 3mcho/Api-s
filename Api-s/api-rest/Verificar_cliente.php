<?php 
require_once('../includes/Client.Class.php');

// Cambiar la condición para permitir solicitudes GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['nombre_usuario'])) {

    $nombre_usuario = $_GET['nombre_usuario'];

    // Verificar si el cliente ya existe
    if (Client::cliente_existe($nombre_usuario)) {
        header('HTTP/1.1 201: EL CLIENTE YA EXISTE');
        echo json_encode(['message' => 'El cliente ya existe.']);
    } else {
        header('HTTP/1.1 404 NO ENCONTRADO');
        echo json_encode(['message' => 'El cliente no existe.']);
    }
} else {
    header('HTTP/1.1 400 SOLICITUD INCORRECTA');
    echo json_encode(['message' => 'Faltan parametros necesarios.']);
}
?>
