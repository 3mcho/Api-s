<?php
require_once('../includes/Client.Class.php');

// Verificar si el método es GET y si el correo electrónico se ha proporcionado en la URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico'])) {
    $correo_electronico = $_GET['correo_electronico'];

    // Llamar a la función para obtener el usuario por correo electrónico
    $usuario_data = Client::get_UsersApp_by_email($correo_electronico);

    if ($usuario_data) {
        // Devolver los datos del usuario en formato JSON
        header('Content-Type: application/json');
        echo json_encode($usuario_data);
    } else {
        // Si no se encuentra el usuario, devolver un mensaje de error
        header('Content-Type: application/json');
        echo json_encode(array("message" => "Usuario no encontrado."));
    }
} else {
    // Si no se proporcionó el correo electrónico o el método no es GET
    header('Content-Type: application/json');
    echo json_encode(array("message" => "Correo electrónico no proporcionado o método no permitido."));
}
?>