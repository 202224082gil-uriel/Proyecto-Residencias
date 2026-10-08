<?php
// actualizar_password.php - Borrar después de usar
include 'conexion.php';

echo "<h2>Actualización de contraseñas</h2>";
echo "<pre>";

$usuarios = [
    'admin@tesch.edu.mx'   => 'admin123',
    'misseal@tesch.edu.mx' => 'residente123',
    'mariana@tesch.edu.mx' => 'mariana123'
];

foreach ($usuarios as $correo => $password_plano) {
    // Generar hash fresco
    $hash = password_hash($password_plano, PASSWORD_BCRYPT);

    // Actualizar en BD
    $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE correo = ?");
    $stmt->bind_param("ss", $hash, $correo);

    if ($stmt->execute()) {
        echo "✅ $correo actualizado\n";
        echo "   Hash: $hash\n";
        echo "   Longitud: " . strlen($hash) . "\n";

        // Verificar inmediatamente
        $verify = password_verify($password_plano, $hash);
        echo "   Verificación: " . ($verify ? "✅ OK" : "❌ FALLO") . "\n\n";
    } else {
        echo "❌ Error actualizando $correo: " . $conexion->error . "\n\n";
    }
}

echo "\n--- Verificación final desde la BD ---\n\n";

foreach ($usuarios as $correo => $password_plano) {
    $stmt = $conexion->prepare("SELECT password FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $ok = password_verify($password_plano, $row['password']);
        echo "$correo → " . ($ok ? "✅ LOGIN OK" : "❌ LOGIN FALLA") . "\n";
    }
}

echo "</pre>";
echo '<p><a href="login.php">Ir al login</a></p>';
?>