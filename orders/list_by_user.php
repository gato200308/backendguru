<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? 0;
    $sql = "SELECT * FROM pedidos WHERE usuario_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedidos = [];
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
    $response['success'] = true;
    $response['pedidos'] = $pedidos;
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>