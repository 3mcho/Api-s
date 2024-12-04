<?php
require_once('../includes/Client.Class.php');

header('Content-Type: application/json');

// Verificar si el método es GET y si se proporciona el fk_contrato
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['fk_contrato'])) {
    $fk_contrato = $_GET['fk_contrato'];

    // Validar que fk_contrato sea un número entero
    if (!ctype_digit($fk_contrato)) {
        echo json_encode([
            'status' => 'A400', // Código de error por solicitud incorrecta
            'message' => 'El valor ingresado no esta permitido.'
        ]);
        exit; // Detener la ejecución
    }

    try {
        // Llamar a la función para consultar los pagos por fk_contrato
        $pagos_data = Client::consultar_pago_por_fk_contrato($fk_contrato);

        if ($pagos_data) {
            // Pagos encontrados: retornar datos en formato JSON
            echo json_encode([
                'status' => 'E001', // Código de éxito
                'data' => $pagos_data
            ]);
        } else {
            // No se encontraron pagos para el contrato
            echo json_encode([
                'status' => 'A102', // Código de error
                'message' => 'No se encontraron pagos para el contrato con ID: ' . $fk_contrato
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
        'message' => 'Falta el parámetro "fk_contrato" o método HTTP incorrecto.'
    ]);
}
?>

