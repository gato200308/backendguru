<?php
$password_plana = "123456";
$hash_en_bd = '$2y$10$e0NRs7Kk/6PZl9YV7nOnS.2SzBj/KbQF0vB7.3W4F1HcU1I4uhKkK';

echo "Contraseña a verificar: " . $password_plana . "<br>";
echo "Hash en BD: " . $hash_en_bd . "<br>";
echo "<br>";

// Verificar si coinciden
if (password_verify($password_plana, $hash_en_bd)) {
    echo "✅ CONTRASEÑA VÁLIDA - Hash coincide con '123456'";
} else {
    echo "❌ CONTRASEÑA INVÁLIDA - Hash NO coincide con '123456'";
}

echo "<br><br>";

// Generar un nuevo hash para '123456'
$nuevo_hash = password_hash($password_plana, PASSWORD_DEFAULT);
echo "Nuevo hash para '123456': " . $nuevo_hash;
?>