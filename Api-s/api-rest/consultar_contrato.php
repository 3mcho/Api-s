<?php
require_once('../includes/Client.class.php');

// Verificar si el método es GET y si el número de contrato se ha proporcionado en la URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['numero_contrato'])) {
    $numero_contrato = $_GET['numero_contrato'];

    // Llamar a la función para consultar el contrato
    $contrato_data = Client::consultar_contrato($numero_contrato);

    if ($contrato_data) {
        // Devolver los datos del contrato en formato JSON
        header('HTTP/1.1 200 OK');
        echo json_encode($contrato_data);
    } else {
        // Si no se encuentra el contrato, devolver un mensaje de error
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array("message" => "Contrato no encontrado."));
    }
} else {
    // Si no se proporcionó el número de contrato o el método no es GET
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array("message" => "Número de contrato no proporcionado o método no permitido."));
}
?>

