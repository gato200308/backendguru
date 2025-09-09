<?php
header('Content-Type: application/json');
require_once 'db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $foto_base64 = null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_base64 = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
    }

    $sql = "UPDATE usuarios SET nombre=?, correo=?, direccion=?, telefono=?, foto_base64=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = 'Error en la preparación de la consulta';
    } else {
        $stmt->bind_param('sssssi', $nombre, $correo, $direccion, $telefono, $foto_base64, $id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Perfil actualizado correctamente';
        } else {
            $response['success'] = false;
            $response['message'] = 'Error al actualizar perfil';
        }
        $stmt->close();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>