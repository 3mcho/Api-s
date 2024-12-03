<?php 
require_once('../includes/Client.Class.php');

header('Content-Type: application/json'); // Mover la cabecera antes de cualquier salida

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico'])) {

    $correo_electronico = $_GET['correo_electronico'];
    $comprobar = Client::usuario_existe($correo_electronico);

    // Verificar si el cliente ya existe
    if ($comprobar) {
        // Devolver los datos como JSON
        echo json_encode($comprobar);
    } else {
        // Devolver un error si no existe el usuario
        echo json_encode(['message' => 'Error', 'status' => 'A102']);
    }
} else {
    // Si faltan parámetros
    echo json_encode(['message' => 'Faltan parametros necesarios.']);  
}
?>

