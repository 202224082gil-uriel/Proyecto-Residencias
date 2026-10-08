<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "PHP funciona correctamente<br>";
echo "Directorio actual: " . __DIR__ . "<br>";

// Verificar conexión
include 'conexion.php';
if ($conexion->connect_error) {
    die("Error BD: " . $conexion->connect_error);
}
echo "Conexión BD exitosa<br>";

// Ver si existe la tabla documentos
$res = $conexion->query("SHOW TABLES");
echo "Tablas en bd_residencias:<br>";
while ($row = $res->fetch_array()) {
    echo "- " . $row[0] . "<br>";
}
?>