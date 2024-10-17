<?php
require_once('../includes/Client.class.php');

// Verificar si el método es GET y si el correo electrónico se ha proporcionado en la URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['correo_electronico'])) {
    $correo_electronico = $_GET['correo_electronico'];

    // Llamar a la función para consultar los contratos por correo electrónico
    $contratos_data = Client::consultar_contratos_por_correo($correo_electronico);

    if ($contratos_data) {
        // Devolver los datos de los contratos en formato JSON
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        echo json_encode($contratos_data);
    } else {
        // Si no se encuentran contratos, devolver un mensaje de error
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array("message" => "No se encontraron contratos para el correo proporcionado."));
    }
} else {
    // Si no se proporcionó el correo electrónico o el método no es GET
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array("message" => "Correo electrónico no proporcionado o método no permitido."));
}
?>