<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $sql = "DELETE FROM metodos_pago WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $response['success'] = $stmt->execute();
    $response['message'] = $response['success'] ? 'Método de pago eliminado' : 'Error al eliminar método de pago';
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>