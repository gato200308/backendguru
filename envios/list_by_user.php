<?php

header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = intval($_POST['usuario_id'] ?? 0);
    $sql = "SELECT e.*, p.id AS pedido_id, p.estado AS estado_pedido
            FROM envios e
            JOIN pedidos p ON e.pedido_id = p.id
            WHERE p.usuario_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $envios = [];
    while ($row = $result->fetch_assoc()) {
        $envios[] = $row;
    }
    $response['success'] = true;
    $response['envios'] = $envios;
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>