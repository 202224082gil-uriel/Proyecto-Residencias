<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// API simple para operaciones CRUD vía AJAX
header('Content-Type: application/json');
include 'conexion.php';

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

function responder($data) {
    echo json_encode($data);
    exit;
}

function error($msg) {
    http_response_code(400);
    echo json_encode(['error' => $msg]);
    exit;
}

switch ($accion) {

    // ---------- ASESORES ----------
    case 'listar_asesores':
        $res = $conexion->query("SELECT * FROM asesores ORDER BY nombre");
        $data = [];
        while ($row = $res->fetch_assoc()) $data[] = $row;
        responder($data);

    case 'crear_asesor':
        $nombre = $_POST['nombre'] ?? '';
        $carrera = $_POST['carrera'] ?? '';
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $cubiculo = $_POST['cubiculo'] ?? '';
        if (!$nombre || !$carrera || !$correo) error('Faltan campos obligatorios');
        $stmt = $conexion->prepare("INSERT INTO asesores (nombre, carrera, correo, telefono, cubiculo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $carrera, $correo, $telefono, $cubiculo);
        if (!$stmt->execute()) error('Error al crear asesor: ' . $conexion->error);
        responder(['ok' => true, 'id' => $stmt->insert_id]);

    case 'eliminar_asesor':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) error('ID inválido');
        // Verificar que no tenga estudiantes
        $res = $conexion->query("SELECT COUNT(*) AS total FROM estudiantes WHERE asesor_id = $id");
        $row = $res->fetch_assoc();
        if ($row['total'] > 0) error('No se puede eliminar: tiene estudiantes asignados');
        $conexion->query("DELETE FROM asesores WHERE id = $id");
        responder(['ok' => true]);

    // ---------- ESTUDIANTES ----------
    case 'listar_estudiantes':
        $res = $conexion->query("
            SELECT e.*, a.nombre AS asesor_nombre 
            FROM estudiantes e 
            LEFT JOIN asesores a ON e.asesor_id = a.id 
            ORDER BY e.id DESC
        ");
        $data = [];
        while ($row = $res->fetch_assoc()) {
            // Cargar checklist
            $checklist = [];
            $r = $conexion->query("SELECT doc_key, estatus FROM documentos_checklist WHERE estudiante_id = " . intval($row['id']));
            while ($c = $r->fetch_assoc()) $checklist[$c['doc_key']] = $c['estatus'];
            $row['documentos'] = $checklist;

            // Cargar evaluación si existe
            $r2 = $conexion->query("SELECT datos_json FROM anexos_llenados WHERE estudiante_id = " . intval($row['id']) . " AND tipo_anexo = 'XXX'");
            if ($r2 && $r2->num_rows > 0) {
                $a = $r2->fetch_assoc();
                $row['evaluacion'] = json_decode($a['datos_json'], true);
            } else {
                $row['evaluacion'] = null;
            }
            $data[] = $row;
        }
        responder($data);

    case 'crear_estudiante':
        $nombre = $_POST['nombre'] ?? '';
        $control = $_POST['control'] ?? '';
        $carrera = $_POST['carrera'] ?? '';
        $proyecto = $_POST['proyecto'] ?? '';
        $empresa = $_POST['empresa'] ?? '';
        $asesor_id = intval($_POST['asesor_id'] ?? 0);
        $asesor_externo = $_POST['asesor_externo'] ?? '';
        $estatus = $_POST['estatus'] ?? 'Registrado';
        if (!$nombre || !$control || !$carrera) error('Faltan campos obligatorios');

        $stmt = $conexion->prepare("INSERT INTO estudiantes (nombre, control, carrera, proyecto, empresa, asesor_id, asesor_externo, estatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiss", $nombre, $control, $carrera, $proyecto, $empresa, $asesor_id, $asesor_externo, $estatus);
        if (!$stmt->execute()) error('Error al crear estudiante: ' . $conexion->error);
        $nuevo_id = $stmt->insert_id;

        // Crear los 20 registros del checklist
        $docs = ['cargaRP','solicitud','liberacionSS','cargaSS','anteproyecto','cartaPres','cartaAceptacion','libActComp','asigAsesor','anexo29_1','infRP_1','anexo29_2','infRP_2','anexo30','infRP_3','infTecnico','cartaTermino','portadaInfTec','actaCalif','encuestaSat'];
        $stmt2 = $conexion->prepare("INSERT INTO documentos_checklist (estudiante_id, doc_key, estatus) VALUES (?, ?, 'pendiente')");
        foreach ($docs as $d) {
            $stmt2->bind_param("is", $nuevo_id, $d);
            $stmt2->execute();
        }
        responder(['ok' => true, 'id' => $nuevo_id]);

    case 'actualizar_estudiante':
        $id = intval($_POST['id'] ?? 0);
        $nombre = $_POST['nombre'] ?? '';
        $control = $_POST['control'] ?? '';
        $carrera = $_POST['carrera'] ?? '';
        $proyecto = $_POST['proyecto'] ?? '';
        $empresa = $_POST['empresa'] ?? '';
        $asesor_id = intval($_POST['asesor_id'] ?? 0);
        $asesor_externo = $_POST['asesor_externo'] ?? '';
        $estatus = $_POST['estatus'] ?? 'Registrado';
        if (!$id) error('ID inválido');
        $stmt = $conexion->prepare("UPDATE estudiantes SET nombre=?, control=?, carrera=?, proyecto=?, empresa=?, asesor_id=?, asesor_externo=?, estatus=? WHERE id=?");
        $stmt->bind_param("sssssissi", $nombre, $control, $carrera, $proyecto, $empresa, $asesor_id, $asesor_externo, $estatus, $id);
        if (!$stmt->execute()) error('Error al actualizar: ' . $conexion->error);
        responder(['ok' => true]);

    case 'eliminar_estudiante':
        $id = intval($_POST['id'] ?? 0);
        if (!$id) error('ID inválido');
        $conexion->query("DELETE FROM estudiantes WHERE id = $id");
        responder(['ok' => true]);

    // ---------- CHECKLIST ----------
    case 'toggle_documento':
        $est_id = intval($_POST['estudiante_id'] ?? 0);
        $doc_key = $_POST['doc_key'] ?? '';
        if (!$est_id || !$doc_key) error('Datos incompletos');
        $estados = ['pendiente', 'entregado', 'aprobado', 'rechazado'];
        $res = $conexion->query("SELECT estatus FROM documentos_checklist WHERE estudiante_id = $est_id AND doc_key = '$doc_key'");
        if ($res->num_rows == 0) error('No existe el registro');
        $actual = $res->fetch_assoc()['estatus'];
        $idx = array_search($actual, $estados);
        $siguiente = $estados[($idx + 1) % count($estados)];
        $conexion->query("UPDATE documentos_checklist SET estatus = '$siguiente' WHERE estudiante_id = $est_id AND doc_key = '$doc_key'");
        responder(['ok' => true, 'nuevo_estatus' => $siguiente]);

    case 'aprobar_todos':
        $est_id = intval($_POST['estudiante_id'] ?? 0);
        if (!$est_id) error('ID inválido');
        $conexion->query("UPDATE documentos_checklist SET estatus = 'aprobado' WHERE estudiante_id = $est_id");
        responder(['ok' => true]);

    // ---------- ANEXOS LLENADOS ----------
    case 'guardar_anexo':
        $est_id = intval($_POST['estudiante_id'] ?? 0);
        $tipo = $_POST['tipo_anexo'] ?? '';
        $datos = $_POST['datos_json'] ?? '';
        if (!$est_id || !$tipo || !$datos) error('Datos incompletos');
        $stmt = $conexion->prepare("INSERT INTO anexos_llenados (estudiante_id, tipo_anexo, datos_json) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE datos_json = VALUES(datos_json)");
        $stmt->bind_param("iss", $est_id, $tipo, $datos);
        if (!$stmt->execute()) error('Error al guardar: ' . $conexion->error);
        responder(['ok' => true]);

    case 'obtener_anexo':
        $est_id = intval($_GET['estudiante_id'] ?? 0);
        $tipo = $_GET['tipo_anexo'] ?? '';
        if (!$est_id || !$tipo) error('Datos incompletos');
        $stmt = $conexion->prepare("SELECT datos_json FROM anexos_llenados WHERE estudiante_id = ? AND tipo_anexo = ?");
        $stmt->bind_param("is", $est_id, $tipo);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows == 0) responder(['datos' => null]);
        $row = $res->fetch_assoc();
        responder(['datos' => json_decode($row['datos_json'], true)]);

    default:
        error('Acción no reconocida');
}
?>