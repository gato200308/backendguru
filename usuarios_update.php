<?php
// usuarios_update.php
header('Content-Type: application/json');
require_once 'auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $fotoUrl = '';

    // Subir imagen si existe
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('user_') . '_' . basename($_FILES['foto']['name']);
        $filePath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $filePath)) {
            $fotoUrl = $filePath;
        }
    }

    // Actualizar usuario
    $sql = "UPDATE usuarios SET nombre=?, correo=?, direccion=?" . ($fotoUrl ? ", foto=?" : "") . " WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($fotoUrl) {
        $stmt->bind_param('ssssi', $nombre, $correo, $direccion, $fotoUrl, $id);
    } else {
        $stmt->bind_param('sssi', $nombre, $correo, $direccion, $id);
    }
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Usuario actualizado correctamente';
        $response['fotoUrl'] = $fotoUrl;
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al actualizar usuario';
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>