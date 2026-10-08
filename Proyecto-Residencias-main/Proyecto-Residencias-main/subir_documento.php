<?php
session_start();
include 'conexion.php';

// 1. Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Solo procesar si es POST con archivo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['archivo'])) {
    header("Location: mi_perfil.php");
    exit();
}

$id_residente = intval($_SESSION['usuario_id']);
$tipo = trim($_POST['tipo_documento'] ?? 'General');
$archivo = $_FILES['archivo'];

// 3. Verificar errores de subida
if ($archivo['error'] !== UPLOAD_ERR_OK) {
    $errores = [
        UPLOAD_ERR_INI_SIZE   => 'El archivo excede el tamaño máximo permitido por el servidor.',
        UPLOAD_ERR_FORM_SIZE  => 'El archivo excede el tamaño máximo del formulario.',
        UPLOAD_ERR_PARTIAL    => 'El archivo se subió parcialmente.',
        UPLOAD_ERR_NO_FILE    => 'No se seleccionó ningún archivo.',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal del servidor.',
        UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
        UPLOAD_ERR_EXTENSION  => 'Una extensión de PHP detuvo la subida.'
    ];
    $msg = $errores[$archivo['error']] ?? 'Error desconocido en la subida.';
    die("Error al subir el archivo: " . $msg);
}

// 4. Validar extensión
$nombre_original = basename($archivo['name']);
$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
$extensiones_permitidas = ['pdf', 'doc', 'docx', 'zip'];

if (!in_array($extension, $extensiones_permitidas)) {
    die("Formato no permitido. Solo se aceptan: PDF, DOC, DOCX, ZIP.");
}

// 5. Validar tamaño (máx 10 MB)
$max_size = 10 * 1024 * 1024;
if ($archivo['size'] > $max_size) {
    die("El archivo es muy grande. Máximo 10 MB.");
}

// 6. Crear carpeta aislada por residente
$directorio = "uploads/residente_" . $id_residente . "/";
if (!file_exists($directorio)) {
    if (!mkdir($directorio, 0777, true)) {
        die("No se pudo crear la carpeta de subida.");
    }
}

// 7. Generar nombre único para evitar colisiones
$nombre_final = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $nombre_original);
$ruta_destino = $directorio . $nombre_final;

// 8. Mover el archivo
if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
    die("Error al mover el archivo al servidor.");
}

// 9. Guardar en la base de datos con estatus 'Pendiente'
$stmt = $conexion->prepare("
    INSERT INTO documentos 
    (usuario_id, nombre_archivo, ruta_archivo, tipo_documento, estatus) 
    VALUES (?, ?, ?, ?, 'Pendiente')
");
$stmt->bind_param("isss", $id_residente, $nombre_original, $ruta_destino, $tipo);

if ($stmt->execute()) {
    // 10. Redirigir de vuelta al perfil con mensaje de éxito
    header("Location: mi_perfil.php?status=success");
    exit();
} else {
    // Si falla la BD, borrar el archivo subido
    @unlink($ruta_destino);
    die("Error al registrar en la BD: " . $conexion->error);
}
?>