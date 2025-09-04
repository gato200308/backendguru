<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$nombre = $data->nombre;
$descripcion = $data->descripcion;
$precio = $data->precio;
$imagen = $data->imagen;
$vendedor_id = $data->vendedor_id;
$rol = $data->rol;

if($rol != 'vendedor'){
    echo json_encode(["success" => false, "message" => "Solo los vendedores pueden actualizar productos"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM productos WHERE id=:id AND vendedor_id=:vendedor_id");
$stmt->bindParam(":id", $id);
$stmt->bindParam(":vendedor_id", $vendedor_id);
$stmt->execute();
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$producto){
    echo json_encode(["success" => false, "message" => "Producto no encontrado o no autorizado"]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE productos SET nombre=:nombre, descripcion=:descripcion, precio=:precio, imagen=:imagen WHERE id=:id");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":precio", $precio);
    $stmt->bindParam(":imagen", $imagen);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Producto actualizado correctamente"]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
