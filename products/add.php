<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once __DIR__ . '/../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $nombre = $data['nombre'] ?? $_POST['nombre'] ?? '';
    $descripcion = $data['descripcion'] ?? $_POST['descripcion'] ?? '';
    $precio = floatval($data['precio'] ?? $_POST['precio'] ?? 0);
    $stock = intval($data['stock'] ?? $_POST['stock'] ?? 0);
    $vendedor_id = intval($data['vendedor_id'] ?? $_POST['vendedor_id'] ?? 0);
    $imagenUrl = '';

    if (isset($data['imagen_base64']) && !empty($data['imagen_base64'])) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('prod_') . '.jpg';
        $filePath = $uploadDir . $fileName;
        file_put_contents($filePath, base64_decode($data['imagen_base64']));
        $imagenUrl = 'uploads/' . $fileName;
    } elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('prod_') . '_' . basename($_FILES['imagen']['name']);
        $filePath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
            $imagenUrl = 'uploads/' . $fileName;
        }
    }

    try {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen, vendedor_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $imagenUrl, $vendedor_id]);
        $response['success'] = true;
        $response['message'] = 'Producto agregado correctamente';
        $response['imagenUrl'] = $imagenUrl;
        $response['id'] = $conn->lastInsertId();
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['message'] = 'Error al agregar producto: ' . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>