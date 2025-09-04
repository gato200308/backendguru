<?php
header("Content-Type: application/json");

$targetDir = "uploads/";
$response = [];

if(isset($_FILES['imagen'])){
    $fileName = basename($_FILES['imagen']['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    $allowTypes = array('jpg','png','jpeg','gif');
    if(in_array(strtolower($fileType), $allowTypes)){
        if(move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)){
            $response['success'] = true;
            $response['message'] = "Imagen subida correctamente";
            $response['ruta'] = $targetFilePath;
        } else {
            $response['success'] = false;
            $response['message'] = "Error al mover la imagen";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Tipo de archivo no permitido";
    }
} else {
    $response['success'] = false;
    $response['message'] = "No se recibió ningún archivo";
}

echo json_encode($response);
?>
