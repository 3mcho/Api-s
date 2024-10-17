<?php 
require_once('../includes/Client.Class.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico'])) {

    $correo_electronico = $_GET['correo_electronico'];

    $comprobar = Client::usuario_existe($correo_electronico);

    // Verificar si el cliente ya existe
    if ($comprobar) {
        header('Content-Type: application/json');
        // La respuesta ya está siendo manejada dentro de la función usuario_existe

    } else {
        header('Content-Type: application/json');
        // La respuesta de error ya está siendo manejada dentro de la función usuario_existe
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Faltan parametros necesarios.']);
}
?>
