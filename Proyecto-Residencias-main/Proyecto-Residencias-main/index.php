<?php
// Incluir la conexión existente a bd_residencias
include 'conexion.php';

// Obtener los documentos guardados en la base de datos MySQL
$documentosBD = [];
$sql = "SELECT id, nombre_archivo, ruta_archivo, tipo_documento, fecha_subida FROM documentos ORDER BY id DESC";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $documentosBD[] = $fila;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SigeRes - Seguimiento de Residencias Profesionales TecNM</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;600;700&display=swap');
        
        :root {
            --color-tecnm-blue: #1B396A;
            --color-tecnm-gold: #B89C5A;
        }
        
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Montserrat', sans-serif; }
        
        .bg-tecnm-blue { background-color: var(--color-tecnm-blue); }
        .text-tecnm-blue { color: var(--color-tecnm-blue); }
        .border-tecnm-blue { border-color: var(--color-tecnm-blue); }
        .bg-tecnm-gold { background-color: var(--color-tecnm-gold); }
        .text-tecnm-gold { color: var(--color-tecnm-gold); }
        .border-tecnm-gold { border-color: var(--color-tecnm-gold); }
        
        /* Scrollbar Personalizado */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar / Navegación Lateral -->
    <aside class="w-full md:w-72 bg-tecnm-blue text-white flex flex-col flex-shrink-0 shadow-xl z-20">
        <div class="p-6 border-b border-blue-950 flex items-center gap-3">
            <div class="bg-white p-2 rounded-lg flex items-center justify-center shadow-md">
                <span class="font-black text-tecnm-blue text-xl tracking-tighter">TecNM</span>
            </div>
            <div>
                <h2 class="text-sm font-bold tracking-wider uppercase">SigeRes</h2>
                <p class="text-[10px] text-blue-200 uppercase tracking-widest">Residencias Profesionales</p>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <button onclick="switchTab('dashboard')" id="btn-tab-dashboard" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all bg-white/10 text-white shadow-sm">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-tecnm-gold"></i> Panel de Control
            </button>
            <button onclick="switchTab('estudiantes')" id="btn-tab-estudiantes" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i data-lucide="users" class="w-5 h-5 text-blue-300"></i> Residentes
            </button>
            <button onclick="switchTab('documentos')" id="btn-tab-documentos" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i data-lucide="files" class="w-5 h-5 text-blue-300"></i> Control de Documentos
            </button>
            <button onclick="switchTab('evaluaciones')" id="btn-tab-evaluaciones" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i data-lucide="award" class="w-5 h-5 text-blue-300"></i> Evaluación Final
            </button>
            <button onclick="switchTab('asesores')" id="btn-tab-asesores" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i data-lucide="user-check" class="w-5 h-5 text-blue-300"></i> Asesores Internos
            </button>
        </nav>

        <div class="p-4 border-t border-blue-950 bg-blue-950/40 text-xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-tecnm-gold text-tecnm-blue flex items-center justify-center font-bold">JD</div>
            <div>
                <p class="font-semibold text-slate-200">Depto. de Residencias</p>
                <p class="text-[10px] text-slate-400">Coordinación TecNM</p>
            </div>
        </div>
    </aside>

    <!-- Contenedor Principal de Contenido -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto custom-scrollbar">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 sticky top-0 z-10">
            <div>
                <h1 id="page-title" class="text-xl font-bold text-slate-800">Panel de Control</h1>
                <p id="page-description" class="text-xs text-slate-500">Métricas generales de las residencias profesionales actuales.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <button onclick="abrirModalSubir()" class="flex items-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-600 px-3.5 py-2 rounded-lg text-xs font-semibold transition-colors">
                    <i data-lucide="upload" class="w-4 h-4"></i> Subir Archivo BD
                </button>
                <button onclick="exportarDatos()" class="flex items-center gap-2 border border-slate-200 hover:bg-slate-50 text-slate-600 px-3.5 py-2 rounded-lg text-xs font-semibold transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i> Exportar Datos
                </button>
                <button onclick="abrirModalEstudiante()" class="flex items-center gap-2 bg-tecnm-blue hover:bg-blue-900 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-sm">
                    <i data-lucide="user-plus" class="w-4 h-4 text-tecnm-gold"></i> Registrar Residente
                </button>
            </div>
        </header>

        <main class="flex-1 p-6 space-y-6">

            <!-- 1. PESTAÑA: PANEL DE CONTROL (DASHBOARD) -->
            <section id="tab-content-dashboard" class="space-y-6 tab-panel">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Residentes Activos</p>
                            <h3 id="stat-total-alumnos" class="text-3xl font-black text-slate-800 mt-1">0</h3>
                            <p class="text-[10px] text-slate-400 mt-1">Cursando residencias</p>
                        </div>
                        <div class="p-4 rounded-xl bg-blue-50 text-tecnm-blue"><i data-lucide="users" class="w-7 h-7"></i></div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Asesores Internos</p>
                            <h3 id="stat-total-asesores" class="text-3xl font-black text-slate-800 mt-1">0</h3>
                            <p class="text-[10px] text-slate-400 mt-1">Asignados a proyectos</p>
                        </div>
                        <div class="p-4 rounded-xl bg-amber-50 text-tecnm-gold"><i data-lucide="user-check" class="w-7 h-7"></i></div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Entregas de Documentos</p>
                            <h3 id="stat-avance-docs" class="text-3xl font-black text-emerald-600 mt-1">0%</h3>
                            <p class="text-[10px] text-slate-400 mt-1">De expedientes completados</p>
                        </div>
                        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="file-check" class="w-7 h-7"></i></div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Casos Liberados</p>
                            <h3 id="stat-liberados" class="text-3xl font-black text-indigo-600 mt-1">0</h3>
                            <p class="text-[10px] text-slate-400 mt-1">Residencias concluidas</p>
                        </div>
                        <div class="p-4 rounded-xl bg-indigo-50 text-indigo-600"><i data-lucide="party-popper" class="w-7 h-7"></i></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs lg:col-span-2 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-bold text-slate-800">Alertas de Avance Documental</h3>
                                <p class="text-xs text-slate-500">Estudiantes que no han completado documentos críticos.</p>
                            </div>
                            <span class="text-xs bg-rose-50 text-rose-600 px-2 py-1 rounded-full font-bold">Atención Requerida</span>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar flex-1">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                                        <th class="py-3 px-4">Estudiante</th>
                                        <th class="py-3 px-4">Carrera</th>
                                        <th class="py-3 px-4">Falta Entregar</th>
                                        <th class="py-3 px-4 text-right">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody id="dashboard-tabla-alertas" class="divide-y divide-slate-100 text-xs"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex flex-col">
                        <h3 class="font-bold text-slate-800 mb-4">Estatus de Residencias</h3>
                        <div class="space-y-4 flex-1 flex flex-col justify-center">
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                    <span>Registrado / Propuesta</span>
                                    <span id="badge-count-registrado" class="font-bold">0</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div id="progress-registrado" class="bg-sky-400 h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                    <span>En Curso / Desarrollo</span>
                                    <span id="badge-count-curso" class="font-bold">0</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div id="progress-curso" class="bg-amber-400 h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                    <span>Evaluado</span>
                                    <span id="badge-count-evaluado" class="font-bold">0</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div id="progress-evaluado" class="bg-emerald-500 h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                                    <span>Liberado (Proceso Concluido)</span>
                                    <span id="badge-count-liberado" class="font-bold">0</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                    <div id="progress-liberado" class="bg-indigo-600 h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. PESTAÑA: ESTUDIANTES -->
            <section id="tab-content-estudiantes" class="space-y-6 tab-panel hidden">
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </span>
                            <input type="text" id="filtro-estudiantes-buscar" oninput="filtrarYRenderizarEstudiantes()" placeholder="Buscar por Nombre, Matrícula, Proyecto o Empresa..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-tecnm-blue text-sm">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <select id="filtro-estudiantes-carrera" onchange="filtrarYRenderizarEstudiantes()" class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-tecnm-blue">
                                <option value="todas">Todas las Carreras</option>
                                <option value="Ing. en Sistemas Computacionales">Sistemas Computacionales</option>
                                <option value="Ing. Industrial">Industrial</option>
                                <option value="Ing. en Gestión Empresarial">Gestión Empresarial</option>
                                <option value="Ing. Electrónica">Electrónica</option>
                                <option value="Ing. Mecatrónica">Mecatrónica</option>
                            </select>
                            <select id="filtro-estudiantes-asesor" onchange="filtrarYRenderizarEstudiantes()" class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-tecnm-blue">
                                <option value="todos">Todos los Asesores</option>
                            </select>
                            <select id="filtro-estudiantes-estatus" onchange="filtrarYRenderizarEstudiantes()" class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-tecnm-blue">
                                <option value="todos">Todos los Estados</option>
                                <option value="Registrado">Registrado</option>
                                <option value="En Curso">En Curso</option>
                                <option value="Evaluado">Evaluado</option>
                                <option value="Liberado">Liberado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                                    <th class="py-4 px-6">Residente / Matrícula</th>
                                    <th class="py-4 px-6">Carrera</th>
                                    <th class="py-4 px-6">Proyecto / Empresa</th>
                                    <th class="py-4 px-6">Asesor Interno</th>
                                    <th class="py-4 px-6">Progreso Docs</th>
                                    <th class="py-4 px-6 text-center">Estatus</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tabla-estudiantes" class="divide-y divide-slate-100 text-sm"></tbody>
                        </table>
                    </div>
                    <div id="estudiantes-vacio" class="hidden p-12 text-center text-slate-400">
                        <i data-lucide="folder-open" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                        <p class="font-bold">No se encontraron residentes con estos criterios</p>
                        <p class="text-xs">Intenta modificando los filtros de búsqueda.</p>
                    </div>
                </div>
            </section>

            <!-- 3. PESTAÑA: CONTROL DE DOCUMENTOS -->
            <section id="tab-content-documentos" class="space-y-6 tab-panel hidden">
                <!-- ARCHIVOS FÍSICOS DESDE MYSQL (BD_RESIDENCIAS) -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-slate-800">Archivos Adjuntos en Base de Datos MySQL</h3>
                        <button onclick="abrirModalSubir()" class="text-xs bg-tecnm-blue text-white px-3 py-1.5 rounded-lg font-bold flex items-center gap-1 shadow-xs">
                            <i data-lucide="upload" class="w-3.5 h-3.5 text-tecnm-gold"></i> Subir Documento
                        </button>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-lg">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold border-b border-slate-100">
                                    <th class="py-3 px-4">#</th>
                                    <th class="py-3 px-4">Documento</th>
                                    <th class="py-3 px-4">Tipo</th>
                                    <th class="py-3 px-4">Fecha Subida</th>
                                    <th class="py-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <?php if (!empty($documentosBD)): ?>
                                    <?php foreach ($documentosBD as $doc): ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3 px-4 font-bold text-slate-400">#<?php echo $doc['id']; ?></td>
                                            <td class="py-3 px-4 font-bold text-slate-800"><?php echo htmlspecialchars($doc['nombre_archivo']); ?></td>
                                            <td class="py-3 px-4 text-xs"><span class="bg-blue-50 text-tecnm-blue px-2.5 py-1 rounded-full font-bold"><?php echo htmlspecialchars($doc['tipo_documento']); ?></span></td>
                                            <td class="py-3 px-4 text-xs text-slate-500"><?php echo $doc['fecha_subida']; ?></td>
                                            <td class="py-3 px-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>" target="_blank" class="p-1.5 hover:bg-slate-100 text-slate-600 rounded-lg transition-colors" title="Ver Documento">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </a>
                                                    <a href="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>" download class="p-1.5 hover:bg-slate-100 text-tecnm-blue rounded-lg transition-colors" title="Descargar Documento">
                                                        <i data-lucide="download" class="w-4 h-4"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-slate-400">No hay archivos registrados en la base de datos MySQL.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CHECKLIST DE DOCUMENTACIÓN OFICIAL (20 DOCS) -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800">Checklist de Documentación Oficial (20 Documentos Obligatorios)</h3>
                            <p class="text-xs text-slate-500">Haz clic sobre los estatus de cada documento para rotar su valor. Pasa el cursor para ver el nombre extendido del anexo.</p>
                        </div>
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                            <input type="text" id="filtro-docs-buscar" oninput="renderizarControlDocumentos()" placeholder="Buscar residente por nombre o matrícula..." class="w-full pl-9 pr-4 py-1.5 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-tecnm-blue bg-slate-50">
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4 text-xs font-semibold py-2 border-y border-slate-100">
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-200"></span> Pendiente</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Entregado (Por Revisar)</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Aprobado</span>
                        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Rechazado</span>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-lg shadow-inner">
                        <table class="w-full text-left border-collapse table-fixed md:table-auto">
                            <thead>
                                <tr id="cabecera-control-documentos" class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-150"></tr>
                            </thead>
                            <tbody id="tabla-control-documentos" class="divide-y divide-slate-100 text-xs"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- 4. PESTAÑA: EVALUACIÓN FINAL -->
            <section id="tab-content-evaluaciones" class="space-y-6 tab-panel hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs lg:col-span-1 flex flex-col h-[550px]">
                        <h3 class="font-bold text-slate-800 mb-2">Seleccionar Alumno</h3>
                        <p class="text-xs text-slate-500 mb-4">Solo se muestran residentes con expediente completo o en curso.</p>
                        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-2 pr-1" id="evaluaciones-lista-estudiantes"></div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs lg:col-span-2 flex flex-col justify-between" id="evaluacion-detalle-panel">
                        <div class="text-center py-12 text-slate-400" id="evaluacion-instrucciones-vacio">
                            <i data-lucide="award" class="w-16 h-16 text-slate-200 mx-auto mb-3"></i>
                            <h4 class="font-bold text-slate-600">Selecciona un residente de la lista</h4>
                            <p class="text-xs">Para comenzar a calificar las evaluaciones de ambos asesores (Interno y Externo).</p>
                        </div>

                        <div id="evaluacion-formulario-activo" class="hidden space-y-5">
                            <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                                <div>
                                    <span id="eval-carrera-badge" class="text-[10px] bg-blue-50 text-tecnm-blue font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">CARRERA</span>
                                    <h3 id="eval-estudiante-nombre" class="text-lg font-bold text-slate-800 mt-2">Nombre Alumno</h3>
                                    <p id="eval-proyecto-nombre" class="text-xs text-slate-500 font-medium">Nombre de Proyecto de Residencia</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 font-semibold">Calificación Final Ponderada</p>
                                    <h4 id="eval-score-total" class="text-3xl font-black text-tecnm-blue mt-0.5">0 / 100</h4>
                                </div>
                            </div>

                            <form id="form-rubrica-evaluacion" onsubmit="guardarEvaluacion(event)" class="space-y-4">
                                <input type="hidden" id="eval-student-id">
                                
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-150">
                                    <p class="text-[11px] text-slate-600 bg-white p-3 rounded-lg border border-slate-100 shadow-2xs">
                                        💡 <strong>Fórmula Oficial de Ponderación TecNM:</strong> Para cada anexo se promedia la evaluación asentada por el Asesor Interno y la del Asesor Externo. Posteriormente se aplican los pesos correspondientes: 10% para el 1er Anexo 29, 10% para el 2do Anexo 29, y 80% para el Anexo 30.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="bg-blue-50/40 p-4 rounded-xl border border-blue-150 space-y-4">
                                        <h4 class="text-xs font-bold text-tecnm-blue uppercase tracking-wider flex items-center gap-1.5 border-b border-blue-200 pb-2">
                                            <i data-lucide="user-check" class="w-4 h-4 text-tecnm-gold"></i> Evaluación Asesor Interno
                                        </h4>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">1er Anexo 29 (Parcial 1)</span>
                                                <span id="crit-interno-anexo29-1-val" class="font-extrabold text-tecnm-blue">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-interno-anexo29-1" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-blue-900">
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">2do Anexo 29 (Parcial 2)</span>
                                                <span id="crit-interno-anexo29-2-val" class="font-extrabold text-tecnm-blue">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-interno-anexo29-2" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-blue-900">
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">Anexo 30 (Final)</span>
                                                <span id="crit-interno-anexo30-val" class="font-extrabold text-tecnm-blue">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-interno-anexo30" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-blue-900">
                                        </div>
                                    </div>

                                    <div class="bg-amber-50/20 p-4 rounded-xl border border-amber-150 space-y-4">
                                        <h4 class="text-xs font-bold text-amber-900 uppercase tracking-wider flex items-center gap-1.5 border-b border-amber-200 pb-2">
                                            <i data-lucide="user" class="w-4 h-4 text-amber-600"></i> Evaluación Asesor Externo
                                        </h4>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">1er Anexo 29 (Parcial 1)</span>
                                                <span id="crit-externo-anexo29-1-val" class="font-extrabold text-amber-700">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-externo-anexo29-1" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-amber-600">
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">2do Anexo 29 (Parcial 2)</span>
                                                <span id="crit-externo-anexo29-2-val" class="font-extrabold text-amber-700">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-externo-anexo29-2" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-amber-600">
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex justify-between text-xs">
                                                <span class="font-bold text-slate-700">Anexo 30 (Final)</span>
                                                <span id="crit-externo-anexo30-val" class="font-extrabold text-amber-700">100 / 100</span>
                                            </div>
                                            <input type="range" id="crit-externo-anexo30" min="0" max="100" value="100" oninput="calcularCalificacionRubrica()" class="w-full accent-amber-600">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 space-y-3">
                                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 pb-1 flex items-center gap-2">
                                        <i data-lucide="calculator" class="w-4 h-4 text-slate-500"></i> Desglose de Promedios Ponderados
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                                        <div class="bg-white p-3 rounded-lg border border-slate-150 shadow-2xs">
                                            <span class="font-bold text-slate-500 block">Promedio 1er Anexo 29</span>
                                            <span id="calc-avg-anexo29-1" class="text-lg font-black text-slate-800">100.0 / 100</span>
                                            <div class="text-[10px] text-slate-400 mt-1 pt-1 border-t border-slate-100 flex justify-between">
                                                <span>Aporte (10%):</span>
                                                <span id="calc-contrib-anexo29-1" class="font-bold text-tecnm-blue">10.0 pts</span>
                                            </div>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-slate-150 shadow-2xs">
                                            <span class="font-bold text-slate-500 block">Promedio 2do Anexo 29</span>
                                            <span id="calc-avg-anexo29-2" class="text-lg font-black text-slate-800">100.0 / 100</span>
                                            <div class="text-[10px] text-slate-400 mt-1 pt-1 border-t border-slate-100 flex justify-between">
                                                <span>Aporte (10%):</span>
                                                <span id="calc-contrib-anexo29-2" class="font-bold text-tecnm-blue">10.0 pts</span>
                                            </div>
                                        </div>
                                        <div class="bg-white p-3 rounded-lg border border-slate-150 shadow-2xs">
                                            <span class="font-bold text-slate-500 block">Promedio Anexo 30</span>
                                            <span id="calc-avg-anexo30" class="text-lg font-black text-slate-800">100.0 / 100</span>
                                            <div class="text-[10px] text-slate-400 mt-1 pt-1 border-t border-slate-100 flex justify-between">
                                                <span>Aporte (80%):</span>
                                                <span id="calc-contrib-anexo30" class="font-bold text-tecnm-blue">80.0 pts</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 flex justify-end gap-3">
                                    <button type="submit" class="w-full bg-tecnm-blue hover:bg-blue-900 text-white font-bold py-2.5 px-4 rounded-lg text-sm transition-all shadow-md">
                                        Registrar Evaluación Consolidada
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. PESTAÑA: ASESORES INTERNOS -->
            <section id="tab-content-asesores" class="space-y-6 tab-panel hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs lg:col-span-1 space-y-4">
                        <h3 class="font-bold text-slate-800">Registrar Asesor Interno</h3>
                        <form id="form-nuevo-asesor" onsubmit="guardarAsesor(event)" class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="asesor-nombre">Nombre Completo *</label>
                                <input type="text" id="asesor-nombre" required placeholder="Ej. Dr. Mario Alberto Juárez" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="asesor-carrera">Carrera *</label>
                                <select id="asesor-carrera" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
                                    <option value="Ingeniería Informática">Ingeniería Informática</option>
                                    <option value="Ingeniería en Electromecánica">Ingeniería en Electromecánica</option>
                                    <option value="Ingeniería Industrial">Ingeniería Industrial</option>
                                    <option value="Ingeniería en Administración">Ingeniería en Administración</option>
                                    <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="asesor-correo">Correo Electrónico *</label>
                                <input type="email" id="asesor-correo" required placeholder="mario.juarez@tecnm.mx" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="asesor-telefono">Teléfono de Contacto *</label>
                                <input type="tel" id="asesor-telefono" required placeholder="Ej. 5512345678" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="asesor-cubiculo">Cubículo / Oficina</label>
                                <input type="text" id="asesor-cubiculo" placeholder="Edificio K - Planta Alta" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            </div>
                            <button type="submit" class="w-full bg-tecnm-blue hover:bg-blue-900 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all shadow-sm">
                                Registrar Docente
                            </button>
                        </form>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs lg:col-span-2 space-y-4">
                        <h3 class="font-bold text-slate-800">Docentes y Carga de Residentes</h3>
                        <p class="text-xs text-slate-500">Muestra la cantidad de estudiantes asignados a cada profesor en este ciclo escolar.</p>
                        
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 text-slate-400 uppercase text-xs font-semibold tracking-wider border-b border-slate-100">
                                        <th class="py-4 px-6">Docente</th>
                                        <th class="py-4 px-6">Carrera</th>
                                        <th class="py-4 px-6">Contacto</th>
                                        <th class="py-4 px-6 text-center">Residentes Asignados</th>
                                        <th class="py-4 px-6 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-asesores" class="divide-y divide-slate-100 text-sm"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- MODAL: SUBIR ARCHIVO A MYSQL -->
    <div id="modal-subir" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4">
            <h3 class="text-base font-bold text-slate-800">Subir Archivo a la Base de Datos</h3>
            <form action="subir.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Tipo de Documento</label>
                    <select name="tipo_documento" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50">
                        <option value="Solicitud">Solicitud</option>
                        <option value="Anteproyecto">Anteproyecto</option>
                        <option value="Anexo XXIX">Anexo XXIX</option>
                        <option value="Anexo XXX">Anexo XXX</option>
                        <option value="Reporte Final">Reporte Final</option>
                        <option value="Carta Liberación">Carta Liberación</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Seleccionar Archivo</label>
                    <input type="file" name="archivo" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-tecnm-blue">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="cerrarModalSubir()" class="flex-1 px-4 py-2 text-sm text-slate-500 border rounded-lg hover:bg-slate-50">Cancelar</button>
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-bold text-white bg-tecnm-blue rounded-lg hover:bg-blue-900">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: REGISTRAR / EDITAR ESTUDIANTE RESIDENTE -->
    <div id="modal-estudiante" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden transform scale-95 transition-transform">
            <div class="bg-tecnm-blue text-white px-6 py-4 flex justify-between items-center">
                <h3 id="modal-estudiante-titulo" class="text-lg font-bold">Registrar Nuevo Residente</h3>
                <button onclick="cerrarModalEstudiante()" class="text-slate-100 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="form-estudiante" onsubmit="guardarEstudiante(event)" class="p-6 space-y-4">
                <input type="hidden" id="form-estudiante-id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-nombre">Nombre del Estudiante *</label>
                        <input type="text" id="est-nombre" required placeholder="Ej. Ana Gabriela López Ramos" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-control">Matrícula *</label>
                        <input type="text" id="est-control" required placeholder="Ej. 21100045" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-carrera">Carrera Profesional *</label>
                        <select id="est-carrera" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                            <option value="Ing. en Sistemas Computacionales">Ing. en Sistemas Computacionales</option>
                            <option value="Ing. Industrial">Ing. Industrial</option>
                            <option value="Ing. en Gestión Empresarial">Ing. en Gestión Empresarial</option>
                            <option value="Ing. Electrónica">Ing. Electrónica</option>
                            <option value="Ing. Mecatrónica">Ing. Mecatrónica</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-asesor">Asesor Interno *</label>
                        <select id="est-asesor" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50"></select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-proyecto">Nombre de Proyecto de Residencia *</label>
                        <input type="text" id="est-proyecto" required placeholder="Ej. Desarrollo de un Sistema ERP para el Sector Metalúrgico" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-empresa">Empresa / Institución Externa *</label>
                        <input type="text" id="est-empresa" required placeholder="Ej. Intel México, Ternium, PEMEX, etc." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-asesor-ext">Asesor Externo (Empresa)</label>
                        <input type="text" id="est-asesor-ext" placeholder="Ej. Ing. Francisco Javier Torres" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1" for="est-estatus">Estatus General *</label>
                        <select id="est-estatus" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-tecnm-blue focus:outline-none bg-slate-50">
                            <option value="Registrado">Registrado (Fase de Propuesta)</option>
                            <option value="En Curso">En Curso (En Desarrollo)</option>
                            <option value="Evaluado">Evaluado</option>
                            <option value="Liberado">Liberado (Proceso Concluido)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalEstudiante()" class="px-4 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-tecnm-blue hover:bg-blue-900 rounded-lg shadow-sm transition-colors">Guardar Alumno</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN -->
    <div id="modal-eliminar-estudiante" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 text-center space-y-4">
            <div class="mx-auto w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600">
                <i data-lucide="trash-2" class="w-6 h-6"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">¿Deseas eliminar este registro de residencia?</h3>
            <p class="text-xs text-slate-500">Esta acción borrará toda la información del residente, incluyendo su expediente de documentos y calificaciones.</p>
            <div class="flex gap-3 pt-2">
                <button onclick="cerrarModalEliminar()" class="flex-1 px-4 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50 border border-slate-200 rounded-lg transition-colors">Cancelar</button>
                <button id="btn-confirmar-eliminar" class="flex-1 px-4 py-2 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors shadow-sm">Eliminar Permanente</button>
            </div>
        </div>
    </div>

    <!-- CONTENEDOR DE NOTIFICACIONES FLOTANTES -->
    <div id="toast-container" class="fixed bottom-5 right-5 space-y-2 z-50 pointer-events-none"></div>

    <script>
        const DOCUMENT_KEYS = [
            { key: "cargaRP", label: "Carga RP", full: "Carga Académica de Residencias Profesionales" },
            { key: "solicitud", label: "Solicitud de RP", full: "Solicitud de Residencias Profesionales" },
            { key: "liberacionSS", label: "Lib. Serv. Social", full: "Liberación de Servicio Social" },
            { key: "cargaSS", label: "Carga Serv. Social", full: "Carga Académica de Servicio Social" },
            { key: "anteproyecto", label: "Anteproyecto Firmado", full: "Anteproyecto Firmado por el Asesor Interno" },
            { key: "cartaPres", label: "Carta Presentación", full: "Carta de Presentación" },
            { key: "cartaAceptacion", label: "Carta Aceptación", full: "Carta de Aceptación" },
            { key: "libActComp", label: "Lib. Act. Comp.", full: "Liberación de Actividades Complementarias" },
            { key: "asigAsesor", label: "Asig. Asesor", full: "Asignación de Asesor" },
            { key: "anexo29_1", label: "1er Anexo 29", full: "1er Anexo 29 (Evaluación Parcial)" },
            { key: "infRP_1", label: "1er Informe RP", full: "1er Informe de seguimiento de RP" },
            { key: "anexo29_2", label: "2do Anexo 29", full: "2do Anexo 29 (Evaluación Parcial)" },
            { key: "infRP_2", label: "2do Informe RP", full: "2do Informe de Seguimiento de RP" },
            { key: "anexo30", label: "Anexo 30", full: "Anexo 30 (Evaluación del Asesor Externo)" },
            { key: "infRP_3", label: "3er Informe RP", full: "3er Informe de Seguimiento de RP" },
            { key: "infTecnico", label: "Informe Técnico", full: "Informe Técnico de Residencias Profesionales" },
            { key: "cartaTermino", label: "Carta Término", full: "Carta de Término de la Empresa" },
            { key: "portadaInfTec", label: "Portada Firmada", full: "Portada del Informe Técnico firmada por el asesor interno" },
            { key: "actaCalif", label: "Acta Calif.", full: "Acta de calificación oficial" },
            { key: "encuestaSat", label: "Encuesta Sat.", full: "Encuesta de satisfacción de Residencias Profesionales" }
        ];

        const asesoresIniciales = [
            { id: "1", nombre: "Dra. Elizabeth Romero Cruz", carrera: "Ingeniería en Sistemas Computacionales", correo: "elizabeth.romero@tecnm.mx", telefono: "5512345678", cubiculo: "Cubiculo S-12" },
            { id: "2", nombre: "Dr. Arturo Gómez Solís", carrera: "Ingeniería Informática", correo: "arturo.gomez@tecnm.mx", telefono: "5587654321", cubiculo: "Cubiculo S-8" },
            { id: "3", nombre: "M.C. Patricia Hernández Ruiz", carrera: "Ingeniería Industrial", correo: "patricia.hernandez@tecnm.mx", telefono: "5524681357", cubiculo: "Cubiculo I-1" },
            { id: "4", nombre: "Ing. Jorge Villalobos Peza", carrera: "Ingeniería en Electromecánica", correo: "jorge.villalobos@tecnm.mx", telefono: "5513579246", cubiculo: "Planta Alta Edificio M" },
            { id: "5", nombre: "Dra. Claudia Díaz Martínez", carrera: "Ingeniería en Administración", correo: "claudia.diaz@tecnm.mx", telefono: "5598765432", cubiculo: "Edificio E-4" }
        ];

        const estudiantesIniciales = [
            { 
                id: "1", nombre: "Carlos Daniel Vázquez Martínez", control: "21100234", carrera: "Ing. en Sistemas Computacionales", proyecto: "Desarrollo de Microservicios para Monitoreo de Red", empresa: "Intel Guadalajara", asesorId: "1", asesorExterno: "Ing. Guillermo Castro", estatus: "En Curso",
                documentos: { cargaRP: "aprobado", solicitud: "aprobado", liberacionSS: "aprobado", cargaSS: "aprobado", anteproyecto: "aprobado", cartaPres: "aprobado", cartaAceptacion: "aprobado", libActComp: "aprobado", asigAsesor: "aprobado", anexo29_1: "entregado", infRP_1: "pendiente", anexo29_2: "pendiente", infRP_2: "pendiente", anexo30: "pendiente", infRP_3: "pendiente", infTecnico: "pendiente", cartaTermino: "pendiente", portadaInfTec: "pendiente", actaCalif: "pendiente", encuestaSat: "pendiente" },
                evaluacion: { calificacion: null, periodo: "Primer Avance", criteriosInterno: [100, 100, 100], criteriosExterno: [100, 100, 100] }
            },
            { 
                id: "2", nombre: "Mariana Alejandra Ruiz Rosas", control: "21100102", carrera: "Ing. en Gestión Empresarial", proyecto: "Optimización de la Cadena de Suministro Farmacéutica", empresa: "Sanofi México", asesorId: "5", asesorExterno: "Lic. Fernanda Beltrán", estatus: "Registrado",
                documentos: { cargaRP: "aprobado", solicitud: "entregado", liberacionSS: "pendiente", cargaSS: "pendiente", anteproyecto: "pendiente", cartaPres: "pendiente", cartaAceptacion: "pendiente", libActComp: "pendiente", asigAsesor: "pendiente", anexo29_1: "pendiente", infRP_1: "pendiente", anexo29_2: "pendiente", infRP_2: "pendiente", anexo30: "pendiente", infRP_3: "pendiente", infTecnico: "pendiente", cartaTermino: "pendiente", portadaInfTec: "pendiente", actaCalif: "pendiente", encuestaSat: "pendiente" },
                evaluacion: { calificacion: null, periodo: "Primer Avance", criteriosInterno: [100, 100, 100], criteriosExterno: [100, 100, 100] }
            },
            { 
                id: "3", nombre: "Fernando Javier Ramos González", control: "20100411", carrera: "Ing. en Sistemas Computacionales", proyecto: "Migración a Nube de Base de Datos Catastral", empresa: "Gobierno del Estado", asesorId: "2", asesorExterno: "Ing. Roberto Solano", estatus: "Liberado",
                documentos: { cargaRP: "aprobado", solicitud: "aprobado", liberacionSS: "aprobado", cargaSS: "aprobado", anteproyecto: "aprobado", cartaPres: "aprobado", cartaAceptacion: "aprobado", libActComp: "aprobado", asigAsesor: "aprobado", anexo29_1: "aprobado", infRP_1: "aprobado", anexo29_2: "aprobado", infRP_2: "aprobado", anexo30: "aprobado", infRP_3: "aprobado", infTecnico: "aprobado", cartaTermino: "aprobado", portadaInfTec: "aprobado", actaCalif: "aprobado", encuestaSat: "aprobado" },
                evaluacion: { calificacion: 96, periodo: "Reporte Final", criteriosInterno: [100, 92, 98], criteriosExterno: [100, 88, 94] }
            }
        ];

        let estudiantes = [];
        let asesores = [];
        let estudianteEliminarId = null;

        window.onload = function() {
            const localEstudiantes = localStorage.getItem('tecnm_estudiantes_data');
            const localAsesores = localStorage.getItem('tecnm_asesores_data');

            if (localEstudiantes) {
                const parsed = JSON.parse(localEstudiantes);
                if (parsed.length > 0 && (!parsed[0].documentos || !parsed[0].documentos.cargaRP || !parsed[0].evaluacion.criteriosInterno)) {
                    estudiantes = [...estudiantesIniciales];
                    localStorage.setItem('tecnm_estudiantes_data', JSON.stringify(estudiantes));
                } else {
                    estudiantes = parsed;
                }
            } else {
                estudiantes = [...estudiantesIniciales];
                localStorage.setItem('tecnm_estudiantes_data', JSON.stringify(estudiantes));
            }

            if (localAsesores) {
                asesores = JSON.parse(localAsesores);
            } else {
                asesores = [...asesoresIniciales];
                localStorage.setItem('tecnm_asesores_data', JSON.stringify(asesores));
            }

            lucide.createIcons();
            actualizarEstructurasSelects();
            renderizarTodo();
        };

        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all text-blue-100 hover:bg-white/5 hover:text-white";
            });

            document.getElementById(`tab-content-${tabId}`).classList.remove('hidden');
            const activeBtn = document.getElementById(`btn-tab-${tabId}`);
            if (activeBtn) activeBtn.className = "nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all bg-white/10 text-white shadow-sm";

            const pageTitle = document.getElementById('page-title');
            const pageDesc = document.getElementById('page-description');

            if (tabId === 'dashboard') {
                pageTitle.innerText = "Panel de Control";
                pageDesc.innerText = "Métricas generales de las residencias profesionales actuales.";
            } else if (tabId === 'estudiantes') {
                pageTitle.innerText = "Directorio de Residentes";
                pageDesc.innerText = "Listado general de estudiantes cursando sus residencias.";
                filtrarYRenderizarEstudiantes();
            } else if (tabId === 'documentos') {
                pageTitle.innerText = "Control de Documentación";
                pageDesc.innerText = "Avance de entregas y estatus de los documentos oficiales exigidos por TecNM.";
                renderizarControlDocumentos();
            } else if (tabId === 'evaluaciones') {
                pageTitle.innerText = "Evaluaciones de Proyecto";
                pageDesc.innerText = "Ingreso consolidado de calificaciones basadas en la rúbrica oficial de residencias.";
                renderizarModuloEvaluaciones();
            } else if (tabId === 'asesores') {
                pageTitle.innerText = "Asesores Internos";
                pageDesc.innerText = "Administración de docentes asesores y carga de alumnos asignados.";
                renderizarAsesores();
            }
            lucide.createIcons();
        }

        function guardarDatosGlobales() {
            localStorage.setItem('tecnm_estudiantes_data', JSON.stringify(estudiantes));
            localStorage.setItem('tecnm_asesores_data', JSON.stringify(asesores));
        }

        function renderizarTodo() {
            renderizarDashboard();
            filtrarYRenderizarEstudiantes();
            renderizarControlDocumentos();
            renderizarModuloEvaluaciones();
            renderizarAsesores();
        }

        function actualizarEstructurasSelects() {
            const selectFiltroAsesores = document.getElementById('filtro-estudiantes-asesor');
            const selectFormEstudiante = document.getElementById('est-asesor');

            selectFiltroAsesores.innerHTML = '<option value="todos">Todos los Asesores</option>';
            selectFormEstudiante.innerHTML = '<option value="" disabled selected>Selecciona un asesor...</option>';

            asesores.forEach(ase => {
                const optFiltro = document.createElement('option');
                optFiltro.value = ase.id;
                optFiltro.innerText = ase.nombre;
                selectFiltroAsesores.appendChild(optFiltro);

                const optForm = document.createElement('option');
                optForm.value = ase.id;
                optForm.innerText = ase.nombre;
                selectFormEstudiante.appendChild(optForm);
            });
        }

        function renderizarDashboard() {
            const totalAlumnos = estudiantes.length;
            const totalAsesores = asesores.length;

            let totalDocsEsperados = totalAlumnos * DOCUMENT_KEYS.length;
            let totalDocsAprobados = 0;
            let totalLiberados = 0;

            let countRegistrado = 0;
            let countCurso = 0;
            let countEvaluado = 0;
            let countLiberado = 0;

            estudiantes.forEach(est => {
                if (est.estatus === 'Registrado') countRegistrado++;
                if (est.estatus === 'En Curso') countCurso++;
                if (est.estatus === 'Evaluado') countEvaluado++;
                if (est.estatus === 'Liberado') {
                    countLiberado++;
                    totalLiberados++;
                }

                if (est.documentos) {
                    Object.values(est.documentos).forEach(status => {
                        if (status === 'aprobado') totalDocsAprobados++;
                    });
                }
            });

            const porcAvance = totalDocsEsperados > 0 ? Math.round((totalDocsAprobados / totalDocsEsperados) * 100) : 0;

            document.getElementById('stat-total-alumnos').innerText = totalAlumnos;
            document.getElementById('stat-total-asesores').innerText = totalAsesores;
            document.getElementById('stat-avance-docs').innerText = `${porcAvance}%`;
            document.getElementById('stat-liberados').innerText = totalLiberados;

            const setProgress = (id, count, total) => {
                const percent = total > 0 ? (count / total) * 100 : 0;
                const progBar = document.getElementById(`progress-${id}`);
                if (progBar) progBar.style.width = `${percent}%`;
                const badCount = document.getElementById(`badge-count-${id}`);
                if (badCount) badCount.innerText = count;
            };

            setProgress('registrado', countRegistrado, totalAlumnos);
            setProgress('curso', countCurso, totalAlumnos);
            setProgress('evaluado', countEvaluado, totalAlumnos);
            setProgress('liberado', countLiberado, totalAlumnos);

            const tbodyAlertas = document.getElementById('dashboard-tabla-alertas');
            tbodyAlertas.innerHTML = "";
            let alertasMostradas = 0;

            estudiantes.forEach(est => {
                if (alertasMostradas >= 5) return;

                let documentoFaltante = "";
                if (est.documentos) {
                    for (let docKeyObj of DOCUMENT_KEYS) {
                        if (est.documentos[docKeyObj.key] !== 'aprobado') {
                            documentoFaltante = docKeyObj.label;
                            break;
                        }
                    }
                }

                if (documentoFaltante !== "") {
                    alertasMostradas++;
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-slate-50/50 transition-colors";
                    tr.innerHTML = `
                        <td class="py-3 px-4 font-semibold text-slate-800">${escapeHTML(est.nombre)}</td>
                        <td class="py-3 px-4 text-slate-500">${escapeHTML(est.carrera)}</td>
                        <td class="py-3 px-4 text-rose-600 font-bold flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> ${documentoFaltante}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${est.estatus === 'En Curso' ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-500'}">
                                ${est.estatus}
                            </span>
                        </td>
                    `;
                    tbodyAlertas.appendChild(tr);
                }
            });

            if (alertasMostradas === 0) {
                tbodyAlertas.innerHTML = `<tr><td colspan="4" class="py-6 text-center text-slate-400">Excelente, no se registran alertas de documentación institucional.</td></tr>`;
            }
            lucide.createIcons();
        }

        function filtrarYRenderizarEstudiantes() {
            const query = document.getElementById('filtro-estudiantes-buscar').value.toLowerCase().trim();
            const carrera = document.getElementById('filtro-estudiantes-carrera').value;
            const asesor = document.getElementById('filtro-estudiantes-asesor').value;
            const estatus = document.getElementById('filtro-estudiantes-estatus').value;

            const filtrados = estudiantes.filter(est => {
                const matchesSearch = est.nombre.toLowerCase().includes(query) || 
                                    est.control.includes(query) || 
                                    est.proyecto.toLowerCase().includes(query) || 
                                    est.empresa.toLowerCase().includes(query);
                const matchesCarrera = carrera === 'todas' || est.carrera === carrera;
                const matchesAsesor = asesor === 'todos' || est.asesorId === asesor;
                const matchesEstatus = estatus === 'todos' || est.estatus === estatus;

                return matchesSearch && matchesCarrera && matchesAsesor && matchesEstatus;
            });

            const tbody = document.getElementById('tabla-estudiantes');
            const divVacio = document.getElementById('estudiantes-vacio');
            tbody.innerHTML = "";

            if (filtrados.length === 0) {
                divVacio.classList.remove('hidden');
            } else {
                divVacio.classList.add('hidden');
                filtrados.forEach(est => {
                    const asesorObj = asesores.find(a => a.id === est.asesorId);
                    const asesorNombre = asesorObj ? asesorObj.nombre : "No Asignado";

                    let docsAprobados = 0;
                    if (est.documentos) {
                        DOCUMENT_KEYS.forEach(docObj => {
                            if (est.documentos[docObj.key] === 'aprobado') docsAprobados++;
                        });
                    }
                    const avanceDocsPorcentaje = Math.round((docsAprobados / DOCUMENT_KEYS.length) * 100);

                    let estatusClass = "bg-slate-100 text-slate-600";
                    if (est.estatus === "Registrado") estatusClass = "bg-sky-50 text-sky-600 border border-sky-100";
                    if (est.estatus === "En Curso") estatusClass = "bg-amber-50 text-amber-600 border border-amber-100";
                    if (est.estatus === "Evaluado") estatusClass = "bg-emerald-50 text-emerald-600 border border-emerald-100";
                    if (est.estatus === "Liberado") estatusClass = "bg-indigo-50 text-indigo-600 border border-indigo-100";

                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-slate-50/40 transition-colors";
                    tr.innerHTML = `
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-800">${escapeHTML(est.nombre)}</div>
                            <div class="text-xs text-slate-400 font-semibold">Matrícula: ${est.control}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-500 font-medium text-xs">${est.carrera}</td>
                        <td class="py-4 px-6">
                            <div class="font-semibold text-slate-700 text-xs truncate max-w-[220px]" title="${escapeHTML(est.proyecto)}">${escapeHTML(est.proyecto)}</div>
                            <div class="text-xs text-slate-400 font-bold">${escapeHTML(est.empresa)}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-semibold text-xs">${escapeHTML(asesorNombre)}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-slate-700">${avanceDocsPorcentaje}%</span>
                                <div class="w-16 bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full" style="width: ${avanceDocsPorcentaje}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold ${estatusClass}">
                                ${est.estatus}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button onclick="editarEstudiante('${est.id}')" class="p-1.5 hover:bg-slate-100 text-blue-600 rounded-lg transition-colors" title="Editar Residente">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <button onclick="confirmarEliminarEstudiante('${est.id}')" class="p-1.5 hover:bg-slate-100 text-rose-600 rounded-lg transition-colors" title="Eliminar">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
            lucide.createIcons();
        }

        function renderizarControlDocumentos() {
            const query = document.getElementById('filtro-docs-buscar').value.toLowerCase().trim();
            const filtrados = estudiantes.filter(e => e.nombre.toLowerCase().includes(query) || e.control.includes(query));

            const theadRow = document.getElementById('cabecera-control-documentos');
            if (theadRow) {
                let headersHTML = `<th class="py-3 px-4 min-w-[220px] sticky left-0 bg-slate-50 z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] border-b border-slate-150">Residente / Matrícula</th>`;
                DOCUMENT_KEYS.forEach((doc, idx) => {
                    headersHTML += `<th class="py-3 px-2 text-center min-w-[140px] border-b border-slate-150 font-semibold" title="${doc.full}">
                        <div class="flex flex-col items-center gap-0.5 cursor-help">
                            <span class="text-[9px] text-slate-400 font-mono font-bold">${idx + 1}/20</span>
                            <span class="truncate max-w-[120px] block text-slate-600 text-ellipsis">${doc.label}</span>
                        </div>
                    </th>`;
                });
                headersHTML += `<th class="py-3 px-4 text-right min-w-[120px] border-b border-slate-150">Acción</th>`;
                theadRow.innerHTML = headersHTML;
            }

            const tbody = document.getElementById('tabla-control-documentos');
            tbody.innerHTML = "";

            filtrados.forEach(est => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";
                
                let tdsHTML = `
                    <td class="py-3.5 px-4 sticky left-0 bg-white z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                        <div class="font-bold text-slate-800 line-clamp-1">${escapeHTML(est.nombre)}</div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">M: ${est.control}</div>
                    </td>
                `;

                DOCUMENT_KEYS.forEach(docObj => {
                    const statusValue = (est.documentos ? est.documentos[docObj.key] : 'pendiente') || 'pendiente';
                    let pillBg = "bg-slate-100 text-slate-500";
                    if (statusValue === "entregado") pillBg = "bg-amber-400 text-white";
                    if (statusValue === "aprobado") pillBg = "bg-emerald-500 text-white";
                    if (statusValue === "rechazado") pillBg = "bg-rose-500 text-white";

                    tdsHTML += `
                        <td class="py-3.5 px-2 text-center">
                            <button onclick="toggleEstatusDocumento('${est.id}', '${docObj.key}')" class="px-2.5 py-1 rounded text-[10px] font-bold tracking-wider uppercase transition-all transform hover:scale-105 shadow-xs ${pillBg}">
                                ${statusValue}
                            </button>
                        </td>
                    `;
                });

                tdsHTML += `
                    <td class="py-3.5 px-4 text-right">
                        <button onclick="aprobarTodaDocumentacion('${est.id}')" class="text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold py-1 px-2.5 rounded-lg border border-emerald-200 transition-colors whitespace-nowrap">
                            Aprobar Todo
                        </button>
                    </td>
                `;

                tr.innerHTML = tdsHTML;
                tbody.appendChild(tr);
            });
        }

        function toggleEstatusDocumento(estudianteId, docKey) {
            const est = estudiantes.find(e => e.id === estudianteId);
            if (!est) return;

            const estados = ["pendiente", "entregado", "aprobado", "rechazado"];
            const estatusActual = (est.documentos ? est.documentos[docKey] : "pendiente") || "pendiente";
            let indexActual = estados.indexOf(estatusActual);
            let siguienteIndex = (indexActual + 1) % estados.length;

            if (!est.documentos) est.documentos = {};
            est.documentos[docKey] = estados[siguienteIndex];
            
            guardarDatosGlobales();
            renderizarControlDocumentos();
            renderizarDashboard();
            const docInfo = DOCUMENT_KEYS.find(d => d.key === docKey);
            const docLabel = docInfo ? docInfo.label : "Documento";
            generarToast(`${docLabel} cambiado a: ${estados[siguienteIndex].toUpperCase()}`, "info");
        }

        function aprobarTodaDocumentacion(estudianteId) {
            const est = estudiantes.find(e => e.id === estudianteId);
            if (!est) return;

            if (!est.documentos) est.documentos = {};
            DOCUMENT_KEYS.forEach(docObj => {
                est.documentos[docObj.key] = "aprobado";
            });

            guardarDatosGlobales();
            renderizarControlDocumentos();
            renderizarDashboard();
            generarToast(`Todos los documentos para ${est.nombre} han sido Aprobados`, "success");
        }

        function renderizarModuloEvaluaciones() {
            const listContainer = document.getElementById('evaluaciones-lista-estudiantes');
            listContainer.innerHTML = "";

            estudiantes.forEach(est => {
                let calificacionBadge = `<span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-full">Sin Evaluar</span>`;
                if (est.evaluacion && est.evaluacion.calificacion !== null) {
                    calificacionBadge = `<span class="bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Evaluado: ${est.evaluacion.calificacion}</span>`;
                }

                const button = document.createElement('button');
                button.onclick = () => cargarEstudianteParaEvaluar(est.id);
                button.className = "w-full text-left p-3 rounded-lg border border-slate-150 hover:bg-slate-50 hover:border-slate-300 transition-all flex justify-between items-center bg-white shadow-xs";
                button.innerHTML = `
                    <div>
                        <div class="font-bold text-slate-800 text-xs">${escapeHTML(est.nombre)}</div>
                        <div class="text-[10px] text-slate-400 font-semibold">Matrícula: ${est.control}</div>
                    </div>
                    ${calificacionBadge}
                `;
                listContainer.appendChild(button);
            });
        }

        function cargarEstudianteParaEvaluar(studentId) {
            const est = estudiantes.find(e => e.id === studentId);
            if (!est) return;

            document.getElementById('evaluacion-instrucciones-vacio').classList.add('hidden');
            const formPanel = document.getElementById('evaluacion-formulario-activo');
            formPanel.classList.remove('hidden');

            document.getElementById('eval-student-id').value = est.id;
            document.getElementById('eval-estudiante-nombre').innerText = est.nombre;
            document.getElementById('eval-proyecto-nombre').innerText = est.proyecto;
            document.getElementById('eval-carrera-badge').innerText = est.carrera;

            if (est.evaluacion && est.evaluacion.calificacion !== null) {
                document.getElementById('eval-score-total').innerText = `${est.evaluacion.calificacion} / 100`;

                const critInt = est.evaluacion.criteriosInterno || est.evaluacion.criterios || [100, 100, 100];
                const critExt = est.evaluacion.criteriosExterno || est.evaluacion.criterios || [100, 100, 100];

                document.getElementById('crit-interno-anexo29-1').value = critInt[0] !== undefined ? critInt[0] : 100;
                document.getElementById('crit-interno-anexo29-2').value = critInt[1] !== undefined ? critInt[1] : 100;
                document.getElementById('crit-interno-anexo30').value = critInt[2] !== undefined ? critInt[2] : 100;

                document.getElementById('crit-externo-anexo29-1').value = critExt[0] !== undefined ? critExt[0] : 100;
                document.getElementById('crit-externo-anexo29-2').value = critExt[1] !== undefined ? critExt[1] : 100;
                document.getElementById('crit-externo-anexo30').value = critExt[2] !== undefined ? critExt[2] : 100;
            } else {
                document.getElementById('eval-score-total').innerText = `100 / 100`;
                document.getElementById('crit-interno-anexo29-1').value = 100;
                document.getElementById('crit-interno-anexo29-2').value = 100;
                document.getElementById('crit-interno-anexo30').value = 100;
                document.getElementById('crit-externo-anexo29-1').value = 100;
                document.getElementById('crit-externo-anexo29-2').value = 100;
                document.getElementById('crit-externo-anexo30').value = 100;
            }

            calcularCalificacionRubrica();
        }

        function calcularCalificacionRubrica() {
            const int1 = parseInt(document.getElementById('crit-interno-anexo29-1').value);
            const int2 = parseInt(document.getElementById('crit-interno-anexo29-2').value);
            const int3 = parseInt(document.getElementById('crit-interno-anexo30').value);

            const ext1 = parseInt(document.getElementById('crit-externo-anexo29-1').value);
            const ext2 = parseInt(document.getElementById('crit-externo-anexo29-2').value);
            const ext3 = parseInt(document.getElementById('crit-externo-anexo30').value);

            document.getElementById('crit-interno-anexo29-1-val').innerText = `${int1} / 100`;
            document.getElementById('crit-interno-anexo29-2-val').innerText = `${int2} / 100`;
            document.getElementById('crit-interno-anexo30-val').innerText = `${int3} / 100`;

            document.getElementById('crit-externo-anexo29-1-val').innerText = `${ext1} / 100`;
            document.getElementById('crit-externo-anexo29-2-val').innerText = `${ext2} / 100`;
            document.getElementById('crit-externo-anexo30-val').innerText = `${ext3} / 100`;

            const avg1 = (int1 + ext1) / 2;
            const avg2 = (int2 + ext2) / 2;
            const avg3 = (int3 + ext3) / 2;

            document.getElementById('calc-avg-anexo29-1').innerText = `${avg1.toFixed(1)} / 100`;
            document.getElementById('calc-avg-anexo29-2').innerText = `${avg2.toFixed(1)} / 100`;
            document.getElementById('calc-avg-anexo30').innerText = `${avg3.toFixed(1)} / 100`;

            const contrib1 = avg1 * 0.10;
            const contrib2 = avg2 * 0.10;
            const contrib3 = avg3 * 0.80;

            document.getElementById('calc-contrib-anexo29-1').innerText = `${contrib1.toFixed(1)} pts`;
            document.getElementById('calc-contrib-anexo29-2').innerText = `${contrib2.toFixed(1)} pts`;
            document.getElementById('calc-contrib-anexo30').innerText = `${contrib3.toFixed(1)} pts`;

            const sumatoria = Math.round(contrib1 + contrib2 + contrib3);
            document.getElementById('eval-score-total').innerText = `${sumatoria} / 100`;
        }

        function guardarEvaluacion(e) {
            e.preventDefault();
            const studentId = document.getElementById('eval-student-id').value;
            const est = estudiantes.find(e => e.id === studentId);
            if (!est) return;

            const int1 = parseInt(document.getElementById('crit-interno-anexo29-1').value);
            const int2 = parseInt(document.getElementById('crit-interno-anexo29-2').value);
            const int3 = parseInt(document.getElementById('crit-interno-anexo30').value);

            const ext1 = parseInt(document.getElementById('crit-externo-anexo29-1').value);
            const ext2 = parseInt(document.getElementById('crit-externo-anexo29-2').value);
            const ext3 = parseInt(document.getElementById('crit-externo-anexo30').value);

            const avg1 = (int1 + ext1) / 2;
            const avg2 = (int2 + ext2) / 2;
            const avg3 = (int3 + ext3) / 2;
            const sumatoria = Math.round((avg1 * 0.10) + (avg2 * 0.10) + (avg3 * 0.80));

            est.evaluacion = {
                calificacion: sumatoria,
                criteriosInterno: [int1, int2, int3],
                criteriosExterno: [ext1, ext2, ext3]
            };
            est.estatus = "Evaluado";

            guardarDatosGlobales();
            renderizarTodo();
            generarToast(`Evaluación consolidada de ${sumatoria} puntos guardada con éxito para ${est.nombre}`, "success");
        }

        function renderizarAsesores() {
            const tbody = document.getElementById('tabla-asesores');
            tbody.innerHTML = "";

            asesores.forEach(ase => {
                const cargaAcademica = estudiantes.filter(e => e.asesorId === ase.id).length;

                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors";
                tr.innerHTML = `
                    <td class="py-4 px-6 font-bold text-slate-800">${escapeHTML(ase.nombre)}</td>
                    <td class="py-4 px-6 text-xs text-slate-500 font-medium">${escapeHTML(ase.carrera || "Sin Carrera")}</td>
                    <td class="py-4 px-6">
                        <div class="text-xs text-slate-700 font-semibold">${escapeHTML(ase.correo)}</div>
                        <div class="text-[10px] text-slate-500 font-medium">Tel: ${escapeHTML(ase.telefono || "N/A")}</div>
                        <div class="text-[10px] text-slate-400 font-bold">${escapeHTML(ase.cubiculo || "Sin Cubículo")}</div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-black ${cargaAcademica >= 5 ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-tecnm-blue'}">
                            ${cargaAcademica} Estudiantes
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <button onclick="eliminarAsesor('${ase.id}')" class="p-1.5 hover:bg-rose-50 text-rose-600 rounded-lg transition-colors" title="Eliminar Docente">
                            <i data-lucide="user-minus" class="w-4 h-4"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            lucide.createIcons();
        }

        function guardarAsesor(e) {
            e.preventDefault();
            const nombre = document.getElementById('asesor-nombre').value.trim();
            const carrera = document.getElementById('asesor-carrera').value;
            const correo = document.getElementById('asesor-correo').value.trim();
            const telefono = document.getElementById('asesor-telefono').value.trim();
            const cubiculo = document.getElementById('asesor-cubiculo').value.trim();

            const nuevoAsesor = {
                id: Date.now().toString(),
                nombre, carrera, correo, telefono, cubiculo
            };

            asesores.push(nuevoAsesor);
            guardarDatosGlobales();
            actualizarEstructurasSelects();
            renderizarTodo();

            document.getElementById('form-nuevo-asesor').reset();
            generarToast(`Asesor ${nombre} registrado con éxito`, "success");
        }

        function eliminarAsesor(id) {
            const tutorados = estudiantes.filter(e => e.asesorId === id);
            if (tutorados.length > 0) {
                generarToast("No se puede eliminar el asesor ya que tiene alumnos asignados.", "warning");
                return;
            }

            asesores = asesores.filter(a => a.id !== id);
            guardarDatosGlobales();
            actualizarEstructurasSelects();
            renderizarTodo();
            generarToast("Asesor removido de la plantilla docente.", "info");
        }

        function abrirModalSubir() { document.getElementById('modal-subir').classList.remove('hidden'); }
        function cerrarModalSubir() { document.getElementById('modal-subir').classList.add('hidden'); }

        function abrirModalEstudiante() {
            document.getElementById('form-estudiante').reset();
            document.getElementById('form-estudiante-id').value = "";
            document.getElementById('modal-estudiante-titulo').innerText = "Registrar Nuevo Residente";
            actualizarEstructurasSelects();

            const modal = document.getElementById('modal-estudiante');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.querySelector('.transform').classList.remove('scale-95'); }, 10);
        }

        function cerrarModalEstudiante() {
            const modal = document.getElementById('modal-estudiante');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 150);
        }

        function editarEstudiante(id) {
            const est = estudiantes.find(e => e.id === id);
            if (!est) return;

            abrirModalEstudiante();
            document.getElementById('modal-estudiante-titulo').innerText = "Editar Datos de Residencia";
            document.getElementById('form-estudiante-id').value = est.id;
            document.getElementById('est-nombre').value = est.nombre;
            document.getElementById('est-control').value = est.control;
            document.getElementById('est-carrera').value = est.carrera;
            document.getElementById('est-asesor').value = est.asesorId;
            document.getElementById('est-proyecto').value = est.proyecto;
            document.getElementById('est-empresa').value = est.empresa;
            document.getElementById('est-asesor-ext').value = est.asesorExterno || "";
            document.getElementById('est-estatus').value = est.estatus;
        }

        function guardarEstudiante(e) {
            e.preventDefault();
            const id = document.getElementById('form-estudiante-id').value;
            const nombre = document.getElementById('est-nombre').value.trim();
            const control = document.getElementById('est-control').value.trim();
            const carrera = document.getElementById('est-carrera').value;
            const asesorId = document.getElementById('est-asesor').value;
            const proyecto = document.getElementById('est-proyecto').value.trim();
            const empresa = document.getElementById('est-empresa').value.trim();
            const asesorExterno = document.getElementById('est-asesor-ext').value.trim();
            const estatus = document.getElementById('est-estatus').value;

            if (id) {
                const index = estudiantes.findIndex(e => e.id === id);
                if (index !== -1) {
                    estudiantes[index] = {
                        ...estudiantes[index],
                        nombre, control, carrera, asesorId, proyecto, empresa, asesorExterno, estatus
                    };
                    generarToast("Información del residente actualizada", "success");
                }
            } else {
                const inicialDocs = {};
                DOCUMENT_KEYS.forEach(docObj => {
                    inicialDocs[docObj.key] = "pendiente";
                });

                const nuevoEst = {
                    id: Date.now().toString(),
                    nombre, control, carrera, asesorId, proyecto, empresa, asesorExterno, estatus,
                    documentos: inicialDocs,
                    evaluacion: {
                        calificacion: null,
                        periodo: "Primer Avance",
                        criteriosInterno: [100, 100, 100],
                        criteriosExterno: [100, 100, 100]
                    }
                };
                estudiantes.push(nuevoEst);
                generarToast("Nuevo residente registrado correctamente", "success");
            }

            guardarDatosGlobales();
            cerrarModalEstudiante();
            renderizarTodo();
        }

        function confirmarEliminarEstudiante(id) {
            estudianteEliminarId = id;
            document.getElementById('modal-eliminar-estudiante').classList.remove('hidden');
            document.getElementById('btn-confirmar-eliminar').onclick = ejecutarEliminacionEstudiante;
        }

        function cerrarModalEliminar() {
            document.getElementById('modal-eliminar-estudiante').classList.add('hidden');
            estudianteEliminarId = null;
        }

        function ejecutarEliminacionEstudiante() {
            if (estudianteEliminarId) {
                estudiantes = estudiantes.filter(e => e.id !== estudianteEliminarId);
                guardarDatosGlobales();
                renderizarTodo();
                generarToast("Registro de residencia eliminado", "warning");
            }
            cerrarModalEliminar();
        }

        function exportarDatos() {
            const dataToExport = {
                estudiantes: estudiantes,
                asesores: asesores,
                exportDate: new Date().toISOString()
            };
            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(dataToExport, null, 2));
            const dlAnchorElem = document.createElement('a');
            dlAnchorElem.setAttribute("href", dataStr);
            dlAnchorElem.setAttribute("download", "tecnm_residencias_backup.json");
            dlAnchorElem.click();
            generarToast("Copia de seguridad descargada exitosamente", "success");
        }

        function generarToast(mensaje, tipo = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let bgClass = 'bg-tecnm-blue border-l-4 border-tecnm-gold';
            let icon = 'check-circle';
            
            if (tipo === 'warning') {
                bgClass = 'bg-rose-600';
                icon = 'alert-triangle';
            } else if (tipo === 'info') {
                bgClass = 'bg-slate-700';
                icon = 'info';
            }

            toast.className = `flex items-center gap-3 ${bgClass} text-white px-4 py-3 rounded-xl shadow-lg transform translate-y-5 opacity-0 transition-all duration-300 pointer-events-auto max-w-sm text-xs font-semibold`;
            toast.innerHTML = `
                <i data-lucide="${icon}" class="w-4 h-4 flex-shrink-0"></i>
                <span class="flex-1 text-ellipsis overflow-hidden">${mensaje}</span>
            `;

            container.appendChild(toast);
            lucide.createIcons();

            setTimeout(() => { toast.classList.remove('translate-y-5', 'opacity-0'); }, 10);
            setTimeout(() => {
                toast.classList.add('translate-y-5', 'opacity-0');
                setTimeout(() => { toast.remove(); }, 300);
            }, 3000);
        }

        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>'"]/g, 
                tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
            );
        }
    </script>
</body>
</html>