<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// el resto de includes y código
header("Content-Type: application/json");
require_once __DIR__ . '/../auth/db.php';

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
