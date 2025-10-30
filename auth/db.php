<?php
$host = "localhost"; // En hosting compartido suele ser localhost
$db_name = "gurubackend_artesanias"; // Probablemente: usuario_nombrebd
$username = "gurubackend"; // Tu usuario de BD (puede ser diferente al del sitio)
$password = "KxXOSG38nSA5nvAUlD8M"; // Tu contraseña de BD (puede ser diferente)
try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>