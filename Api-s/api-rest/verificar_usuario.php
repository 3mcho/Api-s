<?php 
require_once('../includes/Client.Class.php');

// Cambiar la condición para permitir solicitudes GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico']) && isset($_GET['contraseña'])) {

    $correo_electronico = $_GET['correo_electronico'];
    $contraseña = $_GET['contraseña'];

    // Verificar si el cliente ya existe
    if (Client::usuario_existe($correo_electronico, $contraseña)) {
        header('HTTP/1.1 202 EL CLIENTE YA EXISTE');
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
