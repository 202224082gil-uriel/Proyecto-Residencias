<?php
echo "<h2>Test de ruta</h2>";
echo "Directorio actual: " . __DIR__ . "<br>";
echo "Archivo actual: " . __FILE__ . "<br><br>";

echo "¿Existe conexion.php? " . (file_exists('conexion.php') ? "✅ SÍ" : "❌ NO") . "<br>";
echo "¿Existe login.php? " . (file_exists('login.php') ? "✅ SÍ" : "❌ NO") . "<br>";
echo "¿Existe la carpeta uploads? " . (is_dir('uploads') ? "✅ SÍ" : "❌ NO") . "<br><br>";

echo "Contenido de la carpeta:<br>";
echo "<pre>";
print_r(scandir(__DIR__));
echo "</pre>";
?>