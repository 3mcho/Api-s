<?php
require_once('../includes/Client.class.php');

<?php
require_once('../includes/Client.class.php');

// Verificar si el método es GET y si el ID del cliente se ha proporcionado en la URL
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Llamar a la función para consultar los contratos por ID del cliente
    $contratos_data = Client::consultar_contratos_por_id($id_cliente);

    if ($contratos_data) {
        // Devolver los datos de los contratos en formato JSON
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        echo json_encode($contratos_data);
    } else {
        // Si no se encuentran contratos, devolver un mensaje de error
        header('Content-Type: application/json');
        header('HTTP/1.1 404 Not Found');
        echo json_encode(array("message" => "No se encontraron contratos para el ID del cliente proporcionado."));
    }
} else {
    header('Content-Type: application/json');
    // Si no se proporcionó el ID del cliente o el método no es GET
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(array("message" => "ID del cliente no proporcionado o método no permitido."));
}
?>
