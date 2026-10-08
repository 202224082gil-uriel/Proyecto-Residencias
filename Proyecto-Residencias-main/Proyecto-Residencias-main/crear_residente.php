<?php
//session_start();
//require_once 'config/conexion.php';

// Verificación estricta de rol
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    die("No tienes permisos para realizar esta acción.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (empty($correo) || empty($password)) {
        header("Location: panel_admin.php?error=Todos los campos son obligatorios");
        exit();
    }

    try {
        // Validar si el correo ya existe
        $sqlCheck = "SELECT id FROM usuarios WHERE correo = :correo";
        $stmtCheck = $conexion->prepare($sqlCheck);
        $stmtCheck->execute([':correo' => $correo]);

        if ($stmtCheck->rowCount() > 0) {
            header("Location: panel_admin.php?error=El correo ya está registrado");
            exit();
        }

        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar usuario obligatoriamente con el rol 'residente'
        $sqlInsert = "INSERT INTO usuarios (correo, password, rol) VALUES (:correo, :password, 'residente')";
        $stmtInsert = $conexion->prepare($sqlInsert);
        $stmtInsert->execute([
            ':correo' => $correo,
            ':password' => $passwordHash
        ]);

        header("Location: panel_admin.php?mensaje=ok");
        exit();

    } catch (PDOException $e) {
        header("Location: panel_admin.php?error=Error al registrar en la base de datos");
        exit();
    }
} else {
    header("Location: panel_admin.php");
    exit();
}
?>