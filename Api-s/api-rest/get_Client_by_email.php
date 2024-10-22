<?php
require_once('Client.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['email'])) {
        $email = $_GET['email'];
        Client::get_client_by_email($email);
    } else {
        header('HTTP/1.1 400 Correo electrónico requerido');
        echo json_encode(['message' => 'Correo electrónico es requerido']);
    }
} else {
    header('HTTP/1.1 405 Método no permitido');
    echo json_encode(['message' => 'Método no permitido']);
}
?>
