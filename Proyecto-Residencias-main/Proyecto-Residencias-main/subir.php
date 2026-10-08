<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo'])) {
    $tipo_doc = $_POST['tipo_documento'];
    $archivo = $_FILES['archivo'];

    $nombre_original = basename($archivo['name']);
    $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
    
    // Crear un nombre único para evitar duplicados
    $nuevo_nombre = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $nombre_original);
    $ruta_destino = "uploads/" . $nuevo_nombre;

    // Solo permitir ciertos formatos (PDF, DOC, DOCX, ZIP)
    $extensiones_permitidas = array('pdf', 'doc', 'docx', 'zip');

    if (in_array($extension, $extensiones_permitidas)) {
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            // Guardar en la base de datos
            $stmt = $conexion->prepare("INSERT INTO documentos (nombre_archivo, ruta_archivo, tipo_documento) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre_original, $ruta_destino, $tipo_doc);
            
            if ($stmt->execute()) {
                header("Location: index.php?status=success");
                exit();
            } else {
                echo "Error al registrar en la base de datos: " . $conexion->error;
            }
        } else {
            echo "Error al mover el archivo a la carpeta uploads.";
        }
    } else {
        echo "Formato de archivo no permitido. Sube un PDF, DOCX o ZIP.";
    }
}
?>