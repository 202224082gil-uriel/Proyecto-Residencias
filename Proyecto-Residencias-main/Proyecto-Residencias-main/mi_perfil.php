<?php
session_start();
include 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_residente = intval($_SESSION['usuario_id']);

// Obtener datos del residente
$stmt = $conexion->prepare("SELECT nombre_completo, correo, carrera, no_control FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_residente);
$stmt->execute();
$res = $stmt->get_result();
$residente = $res->fetch_assoc();

if (!$residente) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Obtener documentos subidos por este residente
$stmt = $conexion->prepare("
    SELECT id, nombre_archivo, ruta_archivo, tipo_documento, estatus, observaciones, fecha_subida 
    FROM documentos 
    WHERE usuario_id = ? 
    ORDER BY fecha_subida DESC
");
$stmt->bind_param("i", $id_residente);
$stmt->execute();
$res_docs = $stmt->get_result();
$mis_documentos = [];
while ($row = $res_docs->fetch_assoc()) {
    $mis_documentos[] = $row;
}

// Contadores
$stats = ['total' => count($mis_documentos), 'pendientes' => 0, 'aprobados' => 0, 'rechazados' => 0];
foreach ($mis_documentos as $d) {
    if ($d['estatus'] === 'Pendiente') $stats['pendientes']++;
    if ($d['estatus'] === 'Aprobado')  $stats['aprobados']++;
    if ($d['estatus'] === 'Rechazado') $stats['rechazados']++;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - SIGERES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap');
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen">

<!-- Header -->
<header class="bg-blue-900 text-white shadow-lg">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="bg-white text-blue-900 font-black px-3 py-1.5 rounded-lg">TecNM</div>
            <div>
                <div class="text-sm font-bold tracking-wider">SIGERES</div>
                <div class="text-[10px] text-blue-200 uppercase tracking-widest">Residencias Profesionales</div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <div class="text-xs font-bold"><?= htmlspecialchars($residente['nombre_completo']) ?></div>
                <div class="text-[10px] text-blue-200"><?= htmlspecialchars($residente['no_control'] ?? '') ?></div>
            </div>
            <a href="logout.php" 
               class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
                Cerrar Sesión
            </a>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Mensaje de éxito -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 rounded-lg text-sm font-semibold">
            ✅ Documento subido correctamente. Espera la revisión del administrador.
        </div>
    <?php endif; ?>

    <!-- Bienvenida -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h1 class="text-2xl font-black text-slate-800 mb-1">Mi Perfil de Residente</h1>
        <p class="text-sm text-slate-500">
            <?= htmlspecialchars($residente['carrera'] ?? 'Sin carrera') ?> · 
            No. Control: <?= htmlspecialchars($residente['no_control'] ?? '—') ?> · 
            <?= htmlspecialchars($residente['correo']) ?>
        </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total</div>
            <div class="text-3xl font-black text-slate-800 mt-1"><?= $stats['total'] ?></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-sm">
            <div class="text-xs text-amber-600 font-bold uppercase tracking-wider">Pendientes</div>
            <div class="text-3xl font-black text-amber-600 mt-1"><?= $stats['pendientes'] ?></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm">
            <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider">Aprobados</div>
            <div class="text-3xl font-black text-emerald-600 mt-1"><?= $stats['aprobados'] ?></div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-200 shadow-sm">
            <div class="text-xs text-rose-600 font-bold uppercase tracking-wider">Rechazados</div>
            <div class="text-3xl font-black text-rose-600 mt-1"><?= $stats['rechazados'] ?></div>
        </div>
    </div>

    <!-- Formulario de subida -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h2 class="text-sm font-bold text-slate-700 uppercase mb-4">📤 Subir Nuevo Documento</h2>
        <form action="subir_documento.php" method="POST" enctype="multipart/form-data" 
              class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tipo de Documento *</label>
                <select name="tipo_documento" required 
                        class="w-full p-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-900">
                    <option value="Solicitud">Solicitud</option>
                    <option value="Carga RP">Carga RP</option>
                    <option value="Liberación Serv. Social">Liberación Serv. Social</option>
                    <option value="Anteproyecto">Anteproyecto</option>
                    <option value="Carta Presentación">Carta Presentación</option>
                    <option value="Carta Aceptación">Carta Aceptación</option>
                    <option value="Asignación Asesor">Asignación Asesor</option>
                    <option value="Anexo XXIX">Anexo XXIX</option>
                    <option value="Anexo XXX">Anexo XXX</option>
                    <option value="Informe Técnico">Informe Técnico</option>
                    <option value="Carta Término">Carta Término</option>
                    <option value="Acta Calificación">Acta Calificación</option>
                    <option value="Encuesta Satisfacción">Encuesta Satisfacción</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Archivo (PDF, DOC, DOCX, ZIP) *</label>
                <input type="file" name="archivo" required accept=".pdf,.doc,.docx,.zip"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-900 hover:file:bg-blue-100">
            </div>

            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 rounded-lg text-sm transition-colors">
                    Subir Documento
                </button>
            </div>
        </form>
        <p class="text-[10px] text-slate-400 mt-2">Tamaño máximo: 10 MB. Los archivos quedan en espera de revisión del administrador.</p>
    </div>

    <!-- Tabla de mis documentos -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <h2 class="text-sm font-bold text-slate-700 uppercase mb-4">📄 Mis Documentos Subidos</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b">
                        <th class="py-3 px-4">Documento</th>
                        <th class="py-3 px-4">Tipo</th>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4 text-center">Estatus</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($mis_documentos)): ?>
                        <?php foreach ($mis_documentos as $doc): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-800 text-xs"><?= htmlspecialchars($doc['nombre_archivo']) ?></div>
                                    <?php if ($doc['estatus'] === 'Rechazado' && !empty($doc['observaciones'])): ?>
                                        <div class="text-[10px] text-rose-600 mt-0.5">
                                            <strong>Motivo:</strong> <?= htmlspecialchars($doc['observaciones']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="bg-blue-50 text-blue-900 px-2 py-0.5 rounded text-[10px] font-bold">
                                        <?= htmlspecialchars($doc['tipo_documento']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-xs text-slate-500">
                                    <?= date('d/m/Y H:i', strtotime($doc['fecha_subida'])) ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php
                                    $badge = "bg-amber-100 text-amber-800";
                                    if ($doc['estatus'] === 'Aprobado')  $badge = "bg-emerald-100 text-emerald-800";
                                    if ($doc['estatus'] === 'Rechazado') $badge = "bg-rose-100 text-rose-800";
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $badge ?>">
                                        <?= htmlspecialchars($doc['estatus']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-1">
                                    <a href="<?= htmlspecialchars($doc['ruta_archivo']) ?>" target="_blank"
                                       class="text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded font-semibold hover:bg-slate-200">
                                        👁️ Ver
                                    </a>
                                    <a href="<?= htmlspecialchars($doc['ruta_archivo']) ?>" download
                                       class="text-xs bg-blue-50 text-blue-900 px-2.5 py-1 rounded font-semibold hover:bg-blue-100">
                                        📥 Descargar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                <div class="text-4xl mb-2">📭</div>
                                <div class="font-bold">Aún no has subido ningún documento</div>
                                <div class="text-xs mt-1">Usa el formulario de arriba para subir tu primer archivo.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>