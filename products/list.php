<?php
header("Content-Type: application/json");
include "../db.php";

try {
    $stmt = $conn->prepare("SELECT p.*, u.nombre AS vendedor_nombre 
                            FROM productos p 
                            JOIN usuarios u ON p.vendedor_id = u.id
                            ORDER BY p.fecha_creacion DESC");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "productos" => $productos]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
