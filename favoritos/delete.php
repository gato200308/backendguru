<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? 0;
    $producto_id = $_POST['producto_id'] ?? 0;
    $sql = "DELETE FROM favoritos WHERE usuario_id=? AND producto_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $usuario_id, $producto_id);
    $response['success'] = $stmt->execute();
    $response['message'] = $response['success'] ? 'Eliminado de favoritos' : 'Error al eliminar';
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>