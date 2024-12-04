<?php
require_once('../includes/Client.Class.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_contrato'])) {
    $id_contrato = $_GET['id_contrato'];

    try {
        // Llamar a la función para consultar los contratos por ID del cliente
        $contratos_data = Client::consultar_contratos_por_idcontrato($id_contrato);

        if ($contratos_data) {
            // Contratos encontrados: retornar datos en formato JSON
            echo json_encode([
                'status' => 'E001', // Código de éxito
                'data' => $contratos_data
            ]);
        } else {
            // No se encontraron contratos para el cliente
            echo json_encode([
                'status' => 'A102', // Código de error
                'message' => 'No se encontraron contratos para el contrato con ID: ' . $id_cliente
            ]);
        }
    } catch (Exception $e) {
        // Manejo de errores inesperados
        echo json_encode([
            'status' => 'E500', // Código de error interno
            'message' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage()
        ]);
    }
} else {
    // Método no permitido o falta de parámetros
    echo json_encode([
        'status' => 'A400', // Código de error por solicitud incorrecta
        'message' => 'Falta el parámetro "id_cliente" o método HTTP incorrecto.'
    ]);
}
?>
