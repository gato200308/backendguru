<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $vendedor_id = intval($_POST['vendedor_id'] ?? 0);
    $imagenUrl = '';

    // Subir imagen si existe
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
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

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen, vendedor_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdisi', $nombre, $descripcion, $precio, $stock, $imagenUrl, $vendedor_id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Producto agregado correctamente';
        $response['imagenUrl'] = $imagenUrl;
        $response['id'] = $stmt->insert_id;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al agregar producto';
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>
