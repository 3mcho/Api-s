<?php 
require_once('../includes/Client.Class.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['folio']) && isset($_GET['fk_contrato']) && isset($_GET['status']) && isset($_GET['fecha_reporte']) && isset($_GET['problema'])){
        Client:: lev_ticket($_GET['folio'],$_GET['fk_contrato'],$_GET['status'],$_GET['fecha_reporte'],$_GET['problema']);
    }else{
        http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                'message' => 'ERROR:',
                'status' => 'D204'
                ]);
    }

?>
