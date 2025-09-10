<?php
// filepath: c:\Users\santi\Documents\backendguru\products\update.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once __DIR__ . '/../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = intval($data['id'] ?? 0);
    $nombre = $data['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? '';
    $precio = floatval($data['precio'] ?? 0);
    $stock = intval($data['stock'] ?? 0);

    try {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $id]);
        $response['success'] = true;
        $response['message'] = 'Producto actualizado correctamente';
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['message'] = 'Error al actualizar producto: ' . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>