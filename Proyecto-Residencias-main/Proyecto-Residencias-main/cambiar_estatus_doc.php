<?php
session_start();
include 'conexion.php';

// Solo admins
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso no autorizado.");
}

$doc_id = intval($_GET['id'] ?? 0);
$nuevo_estado = $_GET['estado'] ?? '';
$observaciones = $_GET['obs'] ?? '';

$estados_validos = ['Aprobado', 'Rechazado', 'Pendiente'];

if ($doc_id > 0 && in_array($nuevo_estado, $estados_validos)) {
    $admin_id = intval($_SESSION['usuario_id']);
    $stmt = $conexion->prepare("
        UPDATE documentos 
        SET estatus = ?, observaciones = ?, revisado_por = ?, fecha_revision = NOW() 
        WHERE id = ?
    ");
    $stmt->bind_param("ssii", $nuevo_estado, $observaciones, $admin_id, $doc_id);
    $stmt->execute();
}

header("Location: panel_admin.php?status=actualizado");
exit();
?>