<?php
$hash_bd = 'PEGA_AQUI_EL_HASH_DE_LA_BD';
$password_ingresada = 'admin123';

echo "Hash de la BD: <code>" . htmlspecialchars($hash_bd) . "</code><br><br>";
echo "Longitud: " . strlen($hash_bd) . " caracteres<br><br>";
echo "password_verify('admin123', hash_bd) = " . (password_verify($password_ingresada, $hash_bd) ? "✅ TRUE" : "❌ FALSE");
?>