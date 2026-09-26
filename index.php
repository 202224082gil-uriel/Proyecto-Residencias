<?php
// Incluir el archivo de conexión que creamos previamente
include 'conexion.php';

// Consultar los documentos ordenados por la fecha de subida más reciente
$sql_docs = "SELECT * FROM documentos ORDER BY fecha_subida DESC";
$resultado_docs = $conexion->query($sql_docs);
?>
<?php
include 'conexion.php';

// Consultar los documentos guardados
$resultado = $conexion->query("SELECT * FROM documentos ORDER BY fecha_subida DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SigeRes - Control de Documentos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <!-- Módulo Control de Documentos -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
<div class="p-6 border-b border-slate-100 flex justify-between items-center">
    <div>
    <h3 class="font-bold text-slate-800 text-lg">Control de Documentos del Expediente</h3>
    <p class="text-slate-500 text-sm">Carga, consulta y descarga los documentos oficiales de las residencias.</p>
    </div>
    
    <!-- Formulario para subir nuevo documento -->
    <form action="subir.php" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
    <select name="tipo_documento" required class="text-sm border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="Solicitud">Solicitud de Residencia</option>
        <option value="Anteproyecto">Anteproyecto</option>
        <option value="Anexo XXIX">Anexo XXIX</option>
        <option value="Anexo XXX">Anexo XXX</option>
        <option value="Carta Liberación">Carta de Liberación</option>
    </select>
    <input type="file" name="archivo" required class="text-sm text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <i data-lucide="upload" class="w-4 h-4"></i> Subir
    </button>
    </form>
</div>

<!-- Tabla Dinámica de Archivos -->
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold border-b border-slate-200">
        <th class="p-4">Tipo de Documento</th>
        <th class="p-4">Nombre del Archivo</th>
        <th class="p-4">Fecha de Carga</th>
        <th class="p-4 text-center">Acciones</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
        <?php if ($resultado_docs && $resultado_docs->num_rows > 0): ?>
        <?php while ($doc = $resultado_docs->fetch_assoc()): ?>
            <tr class="hover:bg-slate-50 transition-colors">
            <td class="p-4 font-medium text-slate-900">
                <span class="inline-flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-blue-600"></i>
                <?php echo htmlspecialchars($doc['tipo_documento']); ?>
                </span>
            </td>
            <td class="p-4 text-slate-600">
                <?php echo htmlspecialchars($doc['nombre_archivo']); ?>
            </td>
            <td class="p-4 text-slate-500 text-xs">
                <?php echo date('d/m/Y h:i A', strtotime($doc['fecha_subida'])); ?>
            </td>
            <td class="p-4">
                <div class="flex items-center justify-center gap-2">
                <!-- Botón Ver (Abre el PDF/archivo en pestaña nueva) -->
                <a href="<?php echo $doc['ruta_archivo']; ?>" target="_blank" title="Ver documento" class="p-2 text-slate-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                </a>
                <!-- Botón Descargar (Descarga directa a la computadora) -->
                <a href="<?php echo $doc['ruta_archivo']; ?>" download="<?php echo htmlspecialchars($doc['nombre_archivo']); ?>" title="Descargar" class="p-2 text-slate-600 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                </a>
                </div>
            </td>
            </tr>
        <?php endwhile; ?>
        <?php else: ?>
        <tr>
            <td colspan="4" class="p-8 text-center text-slate-400">
            <i data-lucide="folder-open" class="w-10 h-10 mx-auto mb-2 text-slate-300"></i>
            No se han subido documentos al expediente todavía.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
    </table>
</div>
</div>

</body>
</html>