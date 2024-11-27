<?php
    // Incluimos la clase de conexión a la base de datos
    include_once '../includes/client.class.php';

    // Establecemos la cabecera para indicar que se retornará un JSON
    header('Content-Type: application/json');

    // Verificamos si se ha recibido un parámetro fk_cliente
    if (isset($_GET['fk_cliente'])) {
        $fk_cliente = $_GET['fk_cliente'];

        // Creamos una instancia de la clase Client
        $client = new Client();

        // Llamamos al método que obtiene las direcciones por fk_cliente
        $result = $client->usuario_direcciones($fk_cliente);

        // Si se encontraron direcciones, las devolvemos en formato JSON
        if ($result) {
            echo json_encode($result);
        } else {
            // Si no se encuentran direcciones, respondemos con un mensaje de error
            echo json_encode(array('message' => 'No se encontraron direcciones para el cliente especificado.'));
        }
    } else {
        // Si no se recibe el parámetro fk_cliente, mostramos un mensaje de error
        echo json_encode(array('message' => 'Falta el parámetro fk_cliente.'));
    }
?>