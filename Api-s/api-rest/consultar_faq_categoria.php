<?php
require_once('../includes/Client.Class.php');

if (isset($_GET['id_categoria'])) {
    // Obtener el ID de la categoría desde la URL
    $id_categoria = $_GET['id_categoria'];
    Client::get_questions_and_category_by_id($id_categoria);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['message' => 'ID de categoría no proporcionado']);
}
?>
