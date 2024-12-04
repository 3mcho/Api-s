<?php
require_once('../includes/Client.Class.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    try {
        
        $contratos_data = Client::consultar_contratos_por_id($id_cliente);

        if ($contratos_data) {
            
            echo json_encode([
                'status' => 'E001', 
                'data' => $contratos_data
            ]);
        } else {
            
            echo json_encode([
                'status' => 'A102', 
                'message' => 'No se encontraron contratos para el cliente con ID ' . $id_cliente
            ]);
        }
    } catch (Exception $e) {
        
        echo json_encode([
            'status' => 'E500', 
            'message' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage()
        ]);
    }
} else {
    /
    echo json_encode([
        'status' => 'A400', 
        'message' => 'Falta el parámetro "id_cliente" o método HTTP incorrecto.'
    ]);
}
?>
