<?php
session_start();
include 'conexion.php';

// Verificar sesión y rol
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Obtener todos los documentos subidos por residentes
$sql = "SELECT d.*, u.nombre_completo, u.correo, u.no_control, u.carrera
        FROM documentos d
        INNER JOIN usuarios u ON d.usuario_id = u.id
        WHERE d.usuario_id IS NOT NULL
        ORDER BY 
            CASE d.estatus 
                WHEN 'Pendiente' THEN 1 
                WHEN 'Aprobado' THEN 2 
                WHEN 'Rechazado' THEN 3 
            END,
            d.fecha_subida DESC";

$resultado = $conexion->query($sql);
$documentos_alumnos = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $documentos_alumnos[] = $row;
    }
}

// Estadísticas
$stats = [
    'total'      => count($documentos_alumnos),
    'pendientes' => 0,
    'aprobados'  => 0,
    'rechazados' => 0
];
foreach ($documentos_alumnos as $d) {
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
    <title>Panel Admin - SIGERES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap');
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen p-6">

<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Panel de Administración</h1>
            <p class="text-sm text-slate-500">
                Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong>
            </p>
        </div>
        <a href="logout.php" 
           class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors">
            Cerrar Sesión
        </a>
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

    <!-- Tabla de revisión -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-800 text-base">Revisión de Documentos de Residentes</h3>
            <span class="text-xs text-slate-400"><?= count($documentos_alumnos) ?> documento(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b">
                        <th class="py-3 px-4">Residente</th>
                        <th class="py-3 px-4">Documento</th>
                        <th class="py-3 px-4 text-center">Estatus</th>
                        <th class="py-3 px-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (!empty($documentos_alumnos)): ?>
                        <?php foreach ($documentos_alumnos as $doc): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-800"><?= htmlspecialchars($doc['nombre_completo']) ?></div>
                                    <div class="text-xs text-slate-400">
                                        <?= htmlspecialchars($doc['no_control'] ?? '') ?> 
                                        · <?= htmlspecialchars($doc['correo']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-800"><?= htmlspecialchars($doc['nombre_archivo']) ?></div>
                                    <span class="text-xs bg-blue-50 text-blue-900 px-2 py-0.5 rounded font-medium">
                                        <?= htmlspecialchars($doc['tipo_documento']) ?>
                                    </span>
                                    <?php if (!empty($doc['observaciones'])): ?>
                                        <div class="text-xs text-slate-500 mt-1 italic">
                                            Obs: <?= htmlspecialchars($doc['observaciones']) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?php
                                    $badge = "bg-amber-100 text-amber-800";
                                    if ($doc['estatus'] === 'Aprobado')  $badge = "bg-emerald-100 text-emerald-800";
                                    if ($doc['estatus'] === 'Rechazado') $badge = "bg-rose-100 text-rose-800";
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold <?= $badge ?>">
                                        <?= htmlspecialchars($doc['estatus']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                                    <a href="<?= htmlspecialchars($doc['ruta_archivo']) ?>" 
                                       target="_blank" 
                                       class="text-xs bg-slate-100 text-slate-700 px-2.5 py-1 rounded font-semibold hover:bg-slate-200">
                                        👁️ Ver
                                    </a>

                                    <a href="cambiar_estatus_doc.php?id=<?= $doc['id'] ?>&estado=Aprobado" 
                                       class="text-xs bg-emerald-600 text-white px-2.5 py-1 rounded font-bold hover:bg-emerald-700">
                                        ✓ Aprobar
                                    </a>

                                    <a href="cambiar_estatus_doc.php?id=<?= $doc['id'] ?>&estado=Rechazado&obs=Documento+no+valido" 
                                       onclick="return confirm('¿Rechazar este documento?')"
                                       class="text-xs bg-rose-600 text-white px-2.5 py-1 rounded font-bold hover:bg-rose-700">
                                        ✕ Rechazar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">
                                <div class="text-4xl mb-2">📂</div>
                                <div class="font-bold">No hay documentos registrados</div>
                                <div class="text-xs mt-1">Cuando los residentes suban archivos, aparecerán aquí para su revisión.</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>