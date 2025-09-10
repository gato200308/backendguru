<?php
// filepath: c:\Users\santi\Documents\backendguru\products\list_by_vendedor.php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth/db.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $vendedor_id = intval($data['vendedor_id'] ?? $_POST['vendedor_id'] ?? 0);

    try {
        $sql = "SELECT * FROM productos WHERE vendedor_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$vendedor_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response['success'] = true;
        $response['productos'] = $productos;
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['message'] = 'Error al obtener productos: ' . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>