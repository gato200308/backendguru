<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"));

$nombre = $data->nombre;
$descripcion = $data->descripcion;
$precio = $data->precio;
$imagen = $data->imagen;
$vendedor_id = $data->vendedor_id;
$rol = $data->rol;

if($rol != 'vendedor'){
    echo json_encode(["success" => false, "message" => "Solo los vendedores pueden agregar productos"]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, vendedor_id) 
                            VALUES (:nombre, :descripcion, :precio, :imagen, :vendedor_id)");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":imagen", $imagen);
    $stmt->bindParam(":vendedor_id", $vendedor_id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Producto agregado correctamente"]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
