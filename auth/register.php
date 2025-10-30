<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *'); // Para CORS
include __DIR__ . "/db.php";


$data = json_decode(file_get_contents("php://input"));

// Validaciones básicas
if (!isset($data->nombre) || !isset($data->correo) || !isset($data->contrasena) || !isset($data->rol)) {
    echo json_encode(["success" => false, "message" => "Faltan campos requeridos"]);
    exit;
}

$nombre = trim($data->nombre);
$correo = trim($data->correo);
$contrasena = password_hash($data->contrasena, PASSWORD_DEFAULT);
$rol = $data->rol;
$direccion = isset($data->direccion) ? trim($data->direccion) : '';
$telefono = isset($data->telefono) ? trim($data->telefono) : '';

// Validar que el rol sea válido
if (!in_array($rol, ['usuario', 'vendedor'])) {
    echo json_encode(["success" => false, "message" => "Rol inválido"]);
    exit;
}

try {
    // Verificar si el correo ya existe
    $checkStmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = :correo");
    $checkStmt->bindParam(":correo", $correo);
    $checkStmt->execute();

    if ($checkStmt->fetch()) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está registrado"]);
        exit;
    }

    // Insertar usuario con todos los campos
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, rol, direccion, telefono) VALUES (:nombre, :correo, :contrasena, :rol, :direccion, :telefono)");
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":contrasena", $contrasena);
    $stmt->bindParam(":rol", $rol);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
