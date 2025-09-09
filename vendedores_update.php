<?php
header('Content-Type: application/json');
require_once 'auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $foto_base64 = '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_base64 = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
    }

    $sql = "UPDATE vendedores SET nombre=?, correo=?, direccion=?, foto_base64=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $nombre, $correo, $direccion, $foto_base64, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Vendedor actualizado correctamente';
    } else {
        $response['success'] = false;
        $response['message'] = 'Error al actualizar vendedor';
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>