<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$vendedor_id = $data->vendedor_id;
$rol = $data->rol;

if($rol != 'vendedor'){
    echo json_encode(["success" => false, "message" => "Solo los vendedores pueden eliminar productos"]);
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
    $stmt = $conn->prepare("DELETE FROM productos WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Producto eliminado correctamente"]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
