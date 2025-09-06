<?php
header('Content-Type: application/json');
require_once '../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
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

    $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?";
    if ($imagenUrl) {
        $sql .= ", imagen=?";
    }
    $sql .= " WHERE id=?";

    if ($imagenUrl) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdisi', $nombre, $descripcion, $precio, $stock, $imagenUrl, $id);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdii', $nombre, $descripcion, $precio, $stock, $id);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Producto actualizado correctamente';
        $response['imagenUrl'] = $imagenUrl;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al actualizar producto';
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>
