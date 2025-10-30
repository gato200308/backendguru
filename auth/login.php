<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

include __DIR__ . "/db.php";

// Debug completo
$raw_input = file_get_contents("php://input");
error_log("=== DEBUG LOGIN ===");
error_log("Raw input: " . $raw_input);

$data = json_decode(file_get_contents("php://input"));
error_log("JSON decode: " . ($data ? "OK" : "FAILED"));

if ($data === null) {
    error_log("ERROR: JSON es null");
    echo json_encode(["success" => false, "message" => "Error en formato de datos"]);
    exit;
}

$correo = $data->correo;
$contrasena = $data->contrasena;

error_log("Correo: " . $correo);
error_log("Contraseña: " . $contrasena);

// Query SQL
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo=:correo");
$stmt->bindParam(":correo", $correo);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

error_log("Usuario encontrado: " . ($user ? "SÍ" : "NO"));

if ($user) {
    error_log("Hash en BD: " . $user['contrasena']);
    $verificacion = password_verify($contrasena, $user['contrasena']);
    error_log("Verificación: " . ($verificacion ? "OK" : "FALLA"));
    
    if($verificacion){
        error_log("LOGIN EXITOSO");
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        error_log("LOGIN FALLIDO: Contraseña incorrecta");
        echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos"]);
    }
} else {
    error_log("LOGIN FALLIDO: Usuario no encontrado");
    echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos"]);
}

error_log("=== FIN DEBUG ===");
?>