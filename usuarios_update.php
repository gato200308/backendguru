<?php
header('Content-Type: application/json');
require_once 'auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $fotoBase64 = $_POST['foto'] ?? '';

    // Actualizar usuario (incluye la imagen en base64)
    $sql = "UPDATE usuarios SET nombre=?, correo=?, direccion=?, foto_base64=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $nombre, $correo, $direccion, $fotoBase64, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Usuario actualizado correctamente';
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