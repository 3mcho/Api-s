<?php
require_once('../includes/Client.Class.php');

// Verificar si el método es GET y si el correo electrónico se ha proporcionado en la URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Llamar a la función para obtener el usuario por correo electrónico
    $client_data = Client::get_Client_by_id($id_cliente);

    if ($client_data) {
        // Devolver los datos del usuario en formato JSON
        header('HTTP/1.1 200 OK');
        echo json_encode($client_data);
    } else {
        // Si no se encuentra el usuario, devolver un mensaje de error
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array("message" => "Usuario no encontrado."));
    }
} else {
    // Si no se proporcionó el correo electrónico o el método no es GET
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array("message" => "Correo electrónico no proporcionado o método no permitido."));
}
?>