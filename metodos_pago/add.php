<?php

header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? 0;
    $tipo = $_POST['tipo'] ?? '';
    $datos = $_POST['datos'] ?? '';
    $sql = "INSERT INTO metodos_pago (usuario_id, tipo, datos) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $usuario_id, $tipo, $datos);
    $response['success'] = $stmt->execute();
    $response['message'] = $response['success'] ? 'Método de pago agregado' : 'Error al agregar método de pago';
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>