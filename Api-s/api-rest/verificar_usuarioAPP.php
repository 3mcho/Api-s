<?php 
require_once('../includes/Client.Class.php');

// Definir la cabecera antes de cualquier salida
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico'])) {

    $correo_electronico = $_GET['correo_electronico'];
    $usuario = Client::usuario_existe($correo_electronico);

    // Verificar si el usuario existe
    if ($usuario !== false) {
        // Devolver los datos del usuario como JSON
        echo json_encode($usuario);
    } else {
        // Devolver un mensaje de error si el usuario no existe, pero solo una vez
        echo json_encode(['message' => 'Error', 'status' => 'A102']);
    }
} else {
    // Si faltan parámetros
    echo json_encode(['message' => 'Faltan parametros necesarios.']);
}
?>

