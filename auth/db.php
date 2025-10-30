<?php
$host = "localhost"; // En hosting compartido suele ser localhost
$db_name = "artesanias"; // Probablemente: usuario_nombrebd
$username = "root"; // Tu usuario de BD (puede ser diferente al del sitio)
$password = "0525"; // Tu contraseña de BD (puede ser diferente)

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>