<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = intval($_POST['usuario_id'] ?? 0);
    $producto_id = intval($_POST['producto_id'] ?? 0);
    $calificacion = intval($_POST['calificacion'] ?? 5);
    $comentario = $_POST['comentario'] ?? '';
    $sql = "INSERT INTO resenas (producto_id, usuario_id, calificacion, comentario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiis', $producto_id, $usuario_id, $calificacion, $comentario);
    $response['success'] = $stmt->execute();
    $response['message'] = $response['success'] ? 'Reseña agregada' : 'Error al agregar reseña';
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>