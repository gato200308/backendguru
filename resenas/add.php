<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $data) {
    $nombre = $data['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $precio = floatval($data['precio'] ?? 0);
    $stock = intval($data['stock'] ?? 0);
    $imagenBase64 = $data['imagen_base64'] ?? '';
    $vendedor_id = intval($data['vendedor_id'] ?? 0);

    // Guardar imagen en base64 (opcional: puedes guardar en archivo si prefieres)
    // Aquí solo guardamos en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen_base64, vendedor_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdiss', $nombre, $descripcion, $precio, $stock, $imagenBase64, $vendedor_id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Producto creado correctamente';
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al crear producto';
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Datos inválidos o método no permitido';
}

echo json_encode($response);
?>