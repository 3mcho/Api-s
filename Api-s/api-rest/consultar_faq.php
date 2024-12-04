
<?php
require_once('../includes/Client.Class.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id_categoria'])) {
    $id_categoria = $_GET['id_categoria'];

    try {
        
        $faq_data = Client::consultar_faq_por_categoria($id_categoria);

        if ($faq_data) {
            
            echo json_encode([
                'status' => 'E001', 
                'data' => $faq_data
            ]);
        } else {
            
            echo json_encode([
                'status' => 'A102', 
                'message' => 'No se encontraron preguntas frecuentes para la categoría con ID ' . $id_categoria
            ]);
        }
    } catch (Exception $e) {
        
        echo json_encode([
            'status' => 'E500', 
            'message' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage()
        ]);
    }
} else {
    
    echo json_encode([
        'status' => 'A400', 
        'message' => 'Falta el parámetro "id_categoria" o método HTTP incorrecto.'
    ]);
}
?>
