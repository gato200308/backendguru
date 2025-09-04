<?php
header("Content-Type: application/json");
include "../db.php";

$data = json_decode(file_get_contents("php://input"));

$usuario_id = $data->usuario_id;
$total = $data->total;
$productos = $data->productos;

try {
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, total) VALUES (:usuario_id, :total)");
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->bindParam(":total", $total);
    $stmt->execute();
    $pedido_id = $conn->lastInsertId();

    foreach($productos as $p){
        $stmt = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) 
                                VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)");
        $stmt->bindParam(":pedido_id", $pedido_id);
        $stmt->bindParam(":producto_id", $p->id);
        $stmt->bindParam(":cantidad", $p->cantidad);
        $stmt->bindParam(":precio_unitario", $p->precio_unitario);
        $stmt->execute();
    }

    echo json_encode(["success" => true, "message" => "Pedido creado correctamente"]);
} catch(PDOException $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
