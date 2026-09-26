<?php
include 'conexion.php';

// Consultar los documentos cargados en la base de datos
$sql_docs = "SELECT * FROM documentos ORDER BY fecha_subida DESC";
$resultado_docs = $conexion->query($sql_docs);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGERES - TecNM Residencias Profesionales</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Azul Izquierdo -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between p-4 shrink-0">
            <div>
                <div class="flex items-center gap-3 px-2 py-4 mb-6 border-b border-slate-800">
                    <div class="bg-white text-slate-900 font-bold px-2 py-1 rounded text-sm">TecNM</div>
                    <div>
                        <h1 class="font-bold text-sm leading-none">SIGERES</h1>
                        <p class="text-[10px] text-slate-400 mt-1">RESIDENCIAS PROFESIONALES</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Panel de Control
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg text-sm font-medium transition-colors">
                        <i data-lucide="users" class="w-4 h-4"></i> Residentes
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg text-sm font-medium transition-colors">
                        <i data-lucide="folder" class="w-4 h-4"></i> Control de Documentos
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg text-sm font-medium transition-colors">
                        <i data-lucide="calculator" class="w-4 h-4"></i> Evaluación Final
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-3 px-2 py-3 bg-slate-800/50 rounded-lg border border-slate-800">
                <div class="bg-amber-500 text-slate-900 font-bold text-xs p-1.5 rounded">TES</div>
                <div class="text-xs">
                    <p class="font-semibold text-slate-200">Depto. de Residencias</p>
                    <p class="text-[10px] text-slate-400">TESCHA TecNM</p>
                </div>
            </div>
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 overflow-y-auto p-8">
            
            <!-- Encabezado -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Panel de Control</h2>
                    <p class="text-slate-500 text-sm">Métricas generales de las residencias profesionales actuales.</p>
                </div>
                <span class="bg-white border border-slate-200 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 flex items-center gap-2 shadow-sm">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Periodo: Ene - Jun 2026
                </span>
            </div>

            <!-- Métricas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Residentes Activos</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">3</p>
                    </div>
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-lg"><i data-lucide="graduation-cap" class="w-6 h-6"></i></div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Asesores Registrados</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">5</p>
                    </div>
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-lg"><i data-lucide="presentation" class="w-6 h-6"></i></div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Avance Documental</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">85%</p>
                    </div>
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg"><i data-lucide="file-check" class="w-6 h-6"></i></div>
                </div>
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Promedio General</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">91.2 pts</p>
                    </div>
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg"><i data-lucide="award" class="w-6 h-6"></i></div>
                </div>
            </div>

            <!-- Panel Central con Formulario y Botones de Descarga -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Expediente Oficial y Formulario de Carga -->
                <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
                                Expediente Oficial Registrado en Plataforma
                            </h3>
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full">Activo</span>
                        </div>

                        <div class="space-y-2 text-sm text-slate-600 mb-6">
                            <p><strong class="text-slate-800">Estudiante:</strong> VÁZQUEZ BECERRIL MARIANA <span class="text-slate-400">(Matrícula: 202124035)</span></p>
                            <p><strong class="text-slate-800">Carrera:</strong> Ingeniería en Sistemas Computacionales</p>
                            <p><strong class="text-slate-800">Proyecto:</strong> FRONTEND EN TIENDA NUBE CON INTEGRACIÓN APIS</p>
                            <p><strong class="text-slate-800">Empresa:</strong> CODEFLOW S.A.S. DE C.V.</p>
                            <p><strong class="text-slate-800">Asesora Interna:</strong> Mtra. Claudia Guzmán Barrera</p>
                        </div>
                    </div>

                    <!-- Módulo para Subir Archivos -->
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 uppercase mb-2">Subir Documento al Expediente</p>
                        <form action="subir.php" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-2 items-center">
                            <select name="tipo_documento" required class="text-xs border border-slate-300 rounded-lg p-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Solicitud">Solicitud de Residencia</option>
                                <option value="Anteproyecto">Anteproyecto</option>
                                <option value="Anexo XXIX">Anexo XXIX</option>
                                <option value="Anexo XXX">Anexo XXX</option>
                                <option value="Carta Liberación">Carta de Liberación</option>
                            </select>
                            <input type="file" name="archivo" required class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors flex items-center gap-1 ml-auto">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i> Subir
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Lista de Documentos y Botones de Ver / Descargar -->
                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col">
                    <h3 class="font-bold text-slate-800 text-base mb-4 flex items-center gap-2">
                        <i data-lucide="folder-down" class="w-5 h-5 text-blue-600"></i>
                        Documentos del Expediente
                    </h3>

                    <div class="space-y-3 overflow-y-auto max-h-[320px] pr-1">
                        <?php if ($resultado_docs && $resultado_docs->num_rows > 0): ?>
                            <?php while ($doc = $resultado_docs->fetch_assoc()): ?>
                                <div class="p-3 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-between gap-2 hover:border-slate-300 transition-colors">
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-800 text-xs truncate">
                                            <?php echo htmlspecialchars($doc['tipo_documento']); ?>
                                        </p>
                                        <p class="text-[11px] text-slate-500 truncate" title="<?php echo htmlspecialchars($doc['nombre_archivo']); ?>">
                                            <?php echo htmlspecialchars($doc['nombre_archivo']); ?>
                                        </p>
                                    </div>

                                    <!-- Botones Ver (ojo) y Descargar (flecha) -->
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="<?php echo $doc['ruta_archivo']; ?>" target="_blank" title="Ver documento" class="p-1.5 text-slate-600 hover:text-blue-600 hover:bg-blue-100 rounded-md transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?php echo $doc['ruta_archivo']; ?>" download="<?php echo htmlspecialchars($doc['nombre_archivo']); ?>" title="Descargar archivo" class="p-1.5 text-slate-600 hover:text-green-600 hover:bg-green-100 rounded-md transition-colors">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center py-8 text-slate-400">
                                <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p class="text-xs">No hay documentos subidos aún.</p>
                                <p class="text-[10px] text-slate-400 mt-1">Usa el botón "Subir" a la izquierda para agregar uno.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- Inicializar los iconos de Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>