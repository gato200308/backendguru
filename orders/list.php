<?php
header("Content-Type: application/json");
include "../db.php";

$usuario_id = $_GET['usuario_id'];

try {
    $stmt = $conn->prepare("SELECT p.*, u.nombre AS usuario_nombre 
                            FROM pedidos p 
                            JOIN usuarios u ON p.usuario_id = u.id
                            WHERE usuario_id=:usuario_id
                            ORDER BY fecha DESC");
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "pedidos" => $pedidos]);
} catch(PDOException $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
