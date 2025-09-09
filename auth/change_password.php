<?php
header('Content-Type: application/json');
require_once 'db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $contrasena_actual = $_POST['contrasena_actual'] ?? '';
    $contrasena_nueva = $_POST['contrasena_nueva'] ?? '';

    $sql = "SELECT contrasena FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = 'Error en la preparación de la consulta';
    } else {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($hash);
        if ($stmt->fetch() && password_verify($contrasena_actual, $hash)) {
            $stmt->close();
            $nuevo_hash = password_hash($contrasena_nueva, PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET contrasena=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $response['success'] = false;
                $response['message'] = 'Error en la preparación de la consulta de actualización';
            } else {
                $stmt->bind_param('si', $nuevo_hash, $id);
                $stmt->execute();
                $response['success'] = true;
                $response['message'] = 'Contraseña actualizada';
                $stmt->close();
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Contraseña actual incorrecta';
            $stmt->close();
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>