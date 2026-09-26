<?php
/**
 * SigeRes - Sistema de Seguimiento de Residencias Profesionales TecNM / TESCHA
 * Single-file PHP Application for XAMPP / Apache Deployment
 */

// 1. DATA STRUCTURE (SIMULATED DATABASE ARRAY)
$residentes = [
    [
        'id' => 1,
        'matricula' => '202124035',
        'nombre' => 'VÁZQUEZ BECERRIL MARIANA',
        'carrera' => 'Ingeniería en Sistemas Computacionales',
        'proyecto' => 'FRONTEND EN TIENDA NUBE CON INTEGRACIÓN APIS',
        'empresa' => 'CODEFLOW S.A.S. DE C.V.',
        'asesor_interno' => 'Mtra. Claudia Guzmán Barrera',
        'asesor_externo' => 'Ing. Roberto Hernández M.',
        'estatus' => 'En Proceso',
        'avance_doc' => 85,
        'eval_int' => ['p1' => 90, 'p2' => 92, 'final' => 90],
        'eval_ext' => ['p1' => 88, 'p2' => 90, 'final' => 92],
        'documentos' => [
            'solicitud' => 'Aprobado',
            'anteproyecto' => 'Aprobado',
            'dictamen' => 'Aprobado',
            'anexo_xxix_p1' => 'Aprobado',
            'anexo_xxix_p2' => 'Aprobado',
            'anexo_xxx' => 'En Cierre',
            'carta_liberacion' => 'Pendiente'
        ]
    ],
    [
        'id' => 2,
        'matricula' => '202124089',
        'nombre' => 'GARCÍA LÓPEZ CARLOS ALBERTO',
        'carrera' => 'Ingeniería Industrial',
        'proyecto' => 'OPTIMIZACIÓN DE CADENA DE SUMINISTRO Y LOGÍSTICA',
        'empresa' => 'LOGISTIX MEXICANA S.A.',
        'asesor_interno' => 'Dr. Fernando Morales Peña',
        'asesor_externo' => 'Lic. Sofia Ramírez Cruz',
        'estatus' => 'En Proceso',
        'avance_doc' => 70,
        'eval_int' => ['p1' => 85, 'p2' => 88, 'final' => 85],
        'eval_ext' => ['p1' => 80, 'p2' => 85, 'final' => 88],
        'documentos' => [
            'solicitud' => 'Aprobado',
            'anteproyecto' => 'Aprobado',
            'dictamen' => 'Aprobado',
            'anexo_xxix_p1' => 'Aprobado',
            'anexo_xxix_p2' => 'En Cierre',
            'anexo_xxx' => 'Pendiente',
            'carta_liberacion' => 'Pendiente'
        ]
    ],
    [
        'id' => 3,
        'matricula' => '202124102',
        'nombre' => 'MARTÍNEZ SANCHEZ ANA VALERIA',
        'carrera' => 'Ingeniería en Sistemas Computacionales',
        'proyecto' => 'DESARROLLO DE PLATAFORMA ERP WEB MODULAR',
        'empresa' => 'INNOVATECH SOLUTIONS',
        'asesor_interno' => 'Mtra. Claudia Guzmán Barrera',
        'asesor_externo' => 'Ing. Alejandro Silva V.',
        'estatus' => 'Concluido',
        'avance_doc' => 100,
        'eval_int' => ['p1' => 95, 'p2' => 98, 'final' => 96],
        'eval_ext' => ['p1' => 95, 'p2' => 95, 'final' => 98],
        'documentos' => [
            'solicitud' => 'Aprobado',
            'anteproyecto' => 'Aprobado',
            'dictamen' => 'Aprobado',
            'anexo_xxix_p1' => 'Aprobado',
            'anexo_xxix_p2' => 'Aprobado',
            'anexo_xxx' => 'Aprobado',
            'carta_liberacion' => 'Aprobado'
        ]
    ]
];

// Helper function to calculate TecNM grade (10% P1 + 10% P2 + 80% Final)
function calcularPromedioTecNM($p1, $p2, $final) {
    return ($p1 * 0.10) + ($p2 * 0.10) + ($final * 0.80);
}

// Helper function to get TecNM qualitative performance level
function obtenerDesempenoTecNM($calificacion) {
    if ($calificacion >= 95) return ['nivel' => 'Excelente', 'color' => 'emerald'];
    if ($calificacion >= 85) return ['nivel' => 'Notable', 'color' => 'blue'];
    if ($calificacion >= 75) return ['nivel' => 'Bueno', 'color' => 'amber'];
    if ($calificacion >= 70) return ['nivel' => 'Suficiente', 'color' => 'orange'];
    return ['nivel' => 'Insuficiente', 'color' => 'red'];
}

// 2. PROCESS CALCULATOR FORM (POST)
$calculo_resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'calcular_evaluacion') {
    $int_p1 = floatval($_POST['int_p1'] ?? 0);
    $int_p2 = floatval($_POST['int_p2'] ?? 0);
    $int_fn = floatval($_POST['int_fn'] ?? 0);

    $ext_p1 = floatval($_POST['ext_p1'] ?? 0);
    $ext_p2 = floatval($_POST['ext_p2'] ?? 0);
    $ext_fn = floatval($_POST['ext_fn'] ?? 0);

    $prom_int = calcularPromedioTecNM($int_p1, $int_p2, $int_fn);
    $prom_ext = calcularPromedioTecNM($ext_p1, $ext_p2, $ext_fn);
    $prom_final = ($prom_int + $prom_ext) / 2;
    $desempeno = obtenerDesempenoTecNM($prom_final);

    $calculo_resultado = [
        'prom_int' => round($prom_int, 2),
        'prom_ext' => round($prom_ext, 2),
        'prom_final' => round($prom_final, 2),
        'desempeno' => desempeno
    ];
}

// 3. GLOBAL STATS CALCULATIONS
$total_residentes = count($residentes);
$asesores_unicos = [];
$suma_promedios = 0;
$suma_avances = 0;

foreach ($residentes as $res) {
    $asesores_unicos[$res['asesor_interno']] = true;
    $asesores_unicos[$res['asesor_externo']] = true;
    
    $p_int = calcularPromedioTecNM($res['eval_int']['p1'], $res['eval_int']['p2'], $res['eval_int']['final']);
    $p_ext = calcularPromedioTecNM($res['eval_ext']['p1'], $res['eval_ext']['p2'], $res['eval_ext']['final']);
    $suma_promedios += ($p_int + $p_ext) / 2;
    $suma_avances += $res['avance_doc'];
}

$total_asesores = count($asesores_unicos);
$promedio_general = $total_residentes > 0 ? round($suma_promedios / $total_residentes, 1) : 0;
$avance_general = $total_residentes > 0 ? round($suma_avances / $total_residentes, 1) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SigeRes - Seguimiento de Residencias Profesionales TecNM</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col md:flex-row antialiased">

    <!-- Sidebar -->
    <aside class="w-full md:w-72 bg-tecnm-blue text-white flex flex-col flex-shrink-0 shadow-xl z-20">
        <div class="p-5 border-b border-blue-900/60 flex items-center gap-3">
            <div class="bg-white p-2 rounded-xl flex items-center justify-center shadow-md">
                <span class="font-black text-tecnm-blue text-xl tracking-tighter">TecNM</span>
            </div>
            <div>
                <h2 class="text-base font-bold tracking-wider uppercase">SigeRes</h2>
                <p class="text-[10px] text-blue-200 uppercase tracking-widest font-medium">Residencias Profesionales</p>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar">
            <button onclick="switchTab('dashboard')" id="btn-tab-dashboard" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all bg-white/10 text-white shadow-sm border border-white/10">
                <i class="fa-solid fa-chart-pie text-tecnm-gold w-5"></i>
                Panel de Control
            </button>
            <button onclick="switchTab('estudiantes')" id="btn-tab-estudiantes" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-users text-blue-300 w-5"></i>
                Residentes
            </button>
            <button onclick="switchTab('documentos')" id="btn-tab-documentos" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-folder-open text-blue-300 w-5"></i>
                Control de Documentos
            </button>
            <button onclick="switchTab('evaluaciones')" id="btn-tab-evaluaciones" class="nav-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all text-blue-100 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-calculator text-blue-300 w-5"></i>
                Evaluación Final
            </button>
        </nav>

        <div class="p-4 border-t border-blue-900/60 bg-blue-950/40 text-xs flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-tecnm-gold text-tecnm-blue flex items-center justify-center font-bold shadow-inner">
                TES
            </div>
            <div class="overflow-hidden">
                <p class="font-semibold text-slate-100 truncate">Depto. de Residencias</p>
                <p class="text-[11px] text-blue-200/80 truncate">TESCHA TecNM</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto custom-scrollbar">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sticky top-0 z-10 shadow-xs">
            <div>
                <h1 id="page-title" class="text-xl font-bold text-slate-800 tracking-tight">Panel de Control</h1>
                <p id="page-description" class="text-xs text-slate-500 mt-0.5">Métricas generales de las residencias profesionales actuales.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-blue-50 text-tecnm-blue border border-blue-100 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-days"></i>
                    Periodo: Ene - Jun 2026
                </span>
            </div>
        </header>

        <main class="flex-1 p-6 space-y-6">
            <!-- TAB: DASHBOARD -->
            <section id="tab-content-dashboard" class="space-y-6 tab-panel">
                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Residentes Activos</p>
                            <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo $total_residentes; ?></h3>
                        </div>
                        <div class="p-3.5 rounded-xl bg-blue-50 text-tecnm-blue">
                            <i class="fa-solid fa-user-graduate text-2xl"></i>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Asesores Registrados</p>
                            <h3 class="text-3xl font-black text-slate-800 mt-1"><?php echo $total_asesores; ?></h3>
                        </div>
                        <div class="p-3.5 rounded-xl bg-amber-50 text-tecnm-gold">
                            <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Avance Documental</p>
                            <h3 class="text-3xl font-black text-emerald-600 mt-1"><?php echo $avance_general; ?>%</h3>
                        </div>
                        <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-file-circle-check text-2xl"></i>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Promedio General</p>
                            <h3 class="text-3xl font-black text-indigo-600 mt-1"><?php echo $promedio_general; ?> pts</h3>
                        </div>
                        <div class="p-3.5 rounded-xl bg-indigo-50 text-indigo-600">
                            <i class="fa-solid fa-award text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Featured Active Record & Quick Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-base">
                                <i class="fa-solid fa-shield-halved text-tecnm-blue"></i>
                                Expediente Oficial Registrado en Plataforma
                            </h3>
                            <span class="text-xs font-bold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full">Activo</span>
                        </div>
                        <?php $destacado = $residentes[0]; ?>
                        <div class="space-y-3 text-sm">
                            <p><strong>Estudiante:</strong> <?php echo $destacado['nombre']; ?> <span class="text-slate-500">(Matrícula: <?php echo $destacado['matricula']; ?>)</span></p>
                            <p><strong>Carrera:</strong> <?php echo $destacado['carrera']; ?></p>
                            <p><strong>Proyecto:</strong> <?php echo $destacado['proyecto']; ?></p>
                            <p><strong>Empresa:</strong> <?php echo $destacado['empresa']; ?></p>
                            <p><strong>Asesora Interna:</strong> <?php echo $destacado['asesor_interno']; ?></p>
                            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex flex-wrap justify-between items-center gap-2">
                                <span class="text-xs font-semibold text-slate-600">Avance Individual de Expediente:</span>
                                <div class="w-full sm:w-48 bg-slate-200 h-2.5 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full" style="width: <?php echo $destacado['avance_doc']; ?>%"></div>
                                </div>
                                <span class="text-xs font-bold text-emerald-700"><?php echo $destacado['avance_doc']; ?>% Completado</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2 text-base border-b border-slate-100 pb-3">
                            <i class="fa-solid fa-list-check text-tecnm-gold"></i>
                            Estatus Documental TecNM
                        </h3>
                        <div class="space-y-4 text-xs">
                            <div>
                                <div class="flex justify-between font-semibold mb-1">
                                    <span>Anexo XXIX - Evaluaciones Parciales</span>
                                    <span class="text-emerald-600">90%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between font-semibold mb-1">
                                    <span>Anexo XXX - Reporte Final</span>
                                    <span class="text-amber-600">65%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-amber-500 h-full rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between font-semibold mb-1">
                                    <span>Cartas de Liberación</span>
                                    <span class="text-blue-600">40%</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-tecnm-blue h-full rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TAB: RESIDENTES -->
            <section id="tab-content-estudiantes" class="space-y-6 tab-panel hidden">
                <!-- Controls Bar -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-center gap-3 flex-1">
                        <div class="relative w-full sm:w-80">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="student-search-input" onkeyup="filterStudents()" placeholder="Buscar por nombre, matrícula o proyecto..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-tecnm-blue focus:outline-none transition-all">
                        </div>
                        <select id="status-filter" onchange="filterStudents()" class="w-full sm:w-48 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            <option value="ALL">Todos los estatus</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Concluido">Concluido</option>
                        </select>
                    </div>
                    <button onclick="openModal()" class="flex items-center justify-center gap-2 bg-tecnm-blue text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-900 transition-colors shadow-sm">
                        <i class="fa-solid fa-user-plus"></i>
                        Nuevo Residente
                    </button>
                </div>

                <!-- Table Container -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs" id="students-table">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                                    <th class="p-4">Estudiante / Matrícula</th>
                                    <th class="p-4">Proyecto / Empresa</th>
                                    <th class="p-4">Asesores (Int / Ext)</th>
                                    <th class="p-4">Avance Doc.</th>
                                    <th class="p-4">Estatus</th>
                                    <th class="p-4 text-center">Calificación</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($residentes as $res): 
                                    $p_int = calcularPromedioTecNM($res['eval_int']['p1'], $res['eval_int']['p2'], $res['eval_int']['final']);
                                    $p_ext = calcularPromedioTecNM($res['eval_ext']['p1'], $res['eval_ext']['p2'], $res['eval_ext']['final']);
                                    $p_prom = round(($p_int + $p_ext) / 2, 1);
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors student-row" data-search="<?php echo strtolower($res['nombre'] . ' ' . $res['matricula'] . ' ' . $res['proyecto'] . ' ' . $res['empresa']); ?>" data-status="<?php echo $res['estatus']; ?>">
                                    <td class="p-4 font-semibold">
                                        <div class="text-slate-800 font-bold"><?php echo $res['nombre']; ?></div>
                                        <div class="text-slate-400 font-mono text-[11px]">Mat: <?php echo $res['matricula']; ?></div>
                                        <div class="text-slate-500 text-[11px]"><?php echo $res['carrera']; ?></div>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-slate-800 font-medium max-w-xs truncate"><?php echo $res['proyecto']; ?></div>
                                        <div class="text-tecnm-blue font-semibold text-[11px]"><i class="fa-solid fa-building text-[10px] mr-1"></i><?php echo $res['empresa']; ?></div>
                                    </td>
                                    <td class="p-4 text-slate-600">
                                        <div><span class="font-bold text-slate-700">Int:</span> <?php echo $res['asesor_interno']; ?></div>
                                        <div><span class="font-bold text-slate-700">Ext:</span> <?php echo $res['asesor_externo']; ?></div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-slate-200 h-2 rounded-full overflow-hidden">
                                                <div class="bg-emerald-500 h-full" style="width: <?php echo $res['avance_doc']; ?>%"></div>
                                            </div>
                                            <span class="font-bold text-slate-700"><?php echo $res['avance_doc']; ?>%</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <?php if ($res['estatus'] === 'Concluido'): ?>
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-[10px]">Concluido</span>
                                        <?php else: ?>
                                            <span class="bg-blue-100 text-blue-800 font-bold px-2.5 py-1 rounded-full text-[10px]">En Proceso</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-block bg-slate-100 border border-slate-200 text-slate-800 font-black px-2.5 py-1 rounded-lg text-sm">
                                            <?php echo $p_prom; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- TAB: CONTROL DE DOCUMENTOS -->
            <section id="tab-content-documentos" class="space-y-6 tab-panel hidden">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-md-center justify-between gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Seleccionar Residente:</label>
                        <select id="doc-student-select" class="w-full md:w-96 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            <?php foreach ($residentes as $res): ?>
                                <option value="<?php echo $res['id']; ?>"><?php echo $res['nombre']; ?> (<?php echo $res['matricula']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 bg-blue-50 text-tecnm-blue border border-blue-100 px-4 py-2 rounded-xl">
                        <i class="fa-solid fa-folder-closed"></i>
                        <span class="text-xs font-bold">Expediente TecNM VIGENTE</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Solicitud de Residencia</h4>
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">Aprobado</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Registro inicial y carta de intención del estudiante.</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Anteproyecto de Residencia</h4>
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">Aprobado</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Planteamiento del problema, objetivos y cronograma.</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Dictamen de Aprobación</h4>
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">Aprobado</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Emitido por la academia correspondiente del TecNM.</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Anexo XXIX (Evaluación Parcial 1)</h4>
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded text-[10px]">Aprobado</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Ponderación: 10% Asesor Interno + 10% Asesor Externo.</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Anexo XXIX (Evaluación Parcial 2)</h4>
                            <span class="bg-amber-100 text-amber-800 font-bold px-2 py-0.5 rounded text-[10px]">En Revisión</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Ponderación: 10% Asesor Interno + 10% Asesor Externo.</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="font-bold text-xs text-slate-800">Anexo XXX (Reporte Final)</h4>
                            <span class="bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded text-[10px]">Pendiente</span>
                        </div>
                        <p class="text-[11px] text-slate-500">Ponderación: 80% Reporte Final de Residencia.</p>
                    </div>
                </div>
            </section>

            <!-- TAB: EVALUACIÓN FINAL (CALCULADORA TECNM) -->
            <section id="tab-content-evaluaciones" class="space-y-6 tab-panel hidden">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                <i class="fa-solid fa-calculator text-tecnm-blue"></i>
                                Calculadora Oficial de Calificación Final TecNM
                            </h3>
                            <p class="text-xs text-slate-500">Fórmula TecNM: Parcial 1 (10%) + Parcial 2 (10%) + Reporte Final Anexo XXX (80%)</p>
                        </div>
                    </div>

                    <form method="POST" action="" class="space-y-6">
                        <input type="hidden" name="action" value="calcular_evaluacion">
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Internal Adviser -->
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-4">
                                <h4 class="font-bold text-tecnm-blue text-xs uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 pb-2">
                                    <i class="fa-solid fa-user-tie"></i>
                                    Evaluación Asesor Interno
                                </h4>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">1er Seguimiento (Anexo XXIX - 10%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="int_p1" value="<?php echo $_POST['int_p1'] ?? '90'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">2do Seguimiento (Anexo XXIX - 10%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="int_p2" value="<?php echo $_POST['int_p2'] ?? '92'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Reporte Final (Anexo XXX - 80%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="int_fn" value="<?php echo $_POST['int_fn'] ?? '90'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- External Adviser -->
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 space-y-4">
                                <h4 class="font-bold text-amber-700 text-xs uppercase tracking-wider flex items-center gap-2 border-b border-slate-200 pb-2">
                                    <i class="fa-solid fa-building-user"></i>
                                    Evaluación Asesor Externo
                                </h4>
                                
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">1er Seguimiento (Anexo XXIX - 10%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="ext_p1" value="<?php echo $_POST['ext_p1'] ?? '88'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">2do Seguimiento (Anexo XXIX - 10%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="ext_p2" value="<?php echo $_POST['ext_p2'] ?? '90'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1">Reporte Final (Anexo XXX - 80%):</label>
                                        <input type="number" step="0.1" min="0" max="100" name="ext_fn" value="<?php echo $_POST['ext_fn'] ?? '92'; ?>" required class="w-full bg-white border border-slate-200 rounded-lg p-2 text-xs font-bold focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-tecnm-blue text-white font-bold text-xs px-6 py-3 rounded-lg hover:bg-blue-900 transition-colors shadow-md flex items-center gap-2">
                                <i class="fa-solid fa-calculator"></i>
                                Procesar y Calcular Calificación Oficial
                            </button>
                        </div>
                    </form>

                    <!-- Calculation Results Display -->
                    <?php if ($calculo_resultado): ?>
                        <div class="p-5 bg-blue-50 border border-blue-200 rounded-xl space-y-3">
                            <h4 class="font-bold text-tecnm-blue text-sm uppercase">Resultado del Cálculo Ponderado TecNM</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div class="bg-white p-3 rounded-lg border border-blue-100">
                                    <span class="text-slate-500 font-semibold block">Promedio Asesor Interno:</span>
                                    <span class="text-lg font-black text-slate-800"><?php echo $calculo_resultado['prom_int']; ?></span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-blue-100">
                                    <span class="text-slate-500 font-semibold block">Promedio Asesor Externo:</span>
                                    <span class="text-lg font-black text-slate-800"><?php echo $calculo_resultado['prom_ext']; ?></span>
                                </div>
                                <div class="bg-white p-3 rounded-lg border border-blue-100">
                                    <span class="text-slate-500 font-semibold block">Calificación Final TecNM:</span>
                                    <span class="text-lg font-black text-tecnm-blue"><?php echo $calculo_resultado['prom_final']; ?> / 100</span>
                                </div>
                            </div>
                            <div class="pt-2 flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-600">Nivel de Desempeño Cualitativo:</span>
                                <span class="text-xs font-black uppercase px-3 py-1 rounded-full bg-<?php echo $calculo_resultado['desempeno']['color']; ?>-100 text-<?php echo $calculo_resultado['desempeno']['color']; ?>-800">
                                    <?php echo $calculo_resultado['desempeno']['nivel']; ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal Registration / Edit -->
    <div id="student-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden border border-slate-200">
            <div class="bg-tecnm-blue text-white px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-sm">Registro de Nuevo Residente</h3>
                <button onclick="closeModal()" class="text-slate-300 hover:text-white"><i class="fa-solid fa-xmark text-base"></i></button>
            </div>
            <div class="p-6 space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Nombre Completo del Alumno:</label>
                    <input type="text" placeholder="ej. Pérez Gómez Juan" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Matrícula:</label>
                        <input type="text" placeholder="2021XXXXX" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-600 mb-1">Carrera:</label>
                        <select class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                            <option>Ing. en Sistemas Computacionales</option>
                            <option>Ing. Industrial</option>
                            <option>Ing. Electromecánica</option>
                            <option>Lic. en Administración</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Nombre del Proyecto:</label>
                    <input type="text" placeholder="Título oficial registrado" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                </div>
                <div>
                    <label class="block font-bold text-slate-600 mb-1">Empresa / Institución:</label>
                    <input type="text" placeholder="Razón social de la empresa" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 focus:ring-2 focus:ring-tecnm-blue focus:outline-none">
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end gap-2">
                <button onclick="closeModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-lg transition-colors">Cancelar</button>
                <button onclick="closeModal()" class="px-4 py-2 bg-tecnm-blue hover:bg-blue-900 text-white font-bold text-xs rounded-lg transition-colors">Guardar Residente</button>
            </div>
        </div>
    </div>

    <!-- Client-side Navigation & Filter Logic -->
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-white/10', 'text-white', 'border', 'border-white/10');
                btn.classList.add('text-blue-100');
            });

            const activePanel = document.getElementById('tab-content-' + tabId);
            if(activePanel) activePanel.classList.remove('hidden');

            const activeBtn = document.getElementById('btn-tab-' + tabId);
            if(activeBtn) {
                activeBtn.classList.add('bg-white/10', 'text-white', 'border', 'border-white/10');
            }

            // Update Header Titles
            const titles = {
                'dashboard': ['Panel de Control', 'Métricas generales de las residencias profesionales actuales.'],
                'estudiantes': ['Gestión de Residentes', 'Listado general de alumnos inscritos en el programa.'],
                'documentos': ['Control de Documentos TecNM', 'Verificación de anexos, dictámenes y reportes oficiales.'],
                'evaluaciones': ['Evaluación Final TecNM', 'Calculadora y captura de calificaciones ponderadas.']
            };

            if(titles[tabId]) {
                document.getElementById('page-title').innerText = titles[tabId][0];
                document.getElementById('page-description').innerText = titles[tabId][1];
            }
        }

        function filterStudents() {
            const query = document.getElementById('student-search-input').value.toLowerCase();
            const status = document.getElementById('status-filter').value;
            const rows = document.querySelectorAll('.student-row');

            rows.forEach(row => {
                const searchMatch = row.getAttribute('data-search').includes(query);
                const statusMatch = status === 'ALL' || row.getAttribute('data-status') === status;

                if (searchMatch && statusMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function openModal() {
            document.getElementById('student-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('student-modal').classList.add('hidden');
        }

        // Keep active tab on postback if form was submitted
        <?php if ($calculo_resultado): ?>
            switchTab('evaluaciones');
        <?php endif; ?>
    </script>
</body>
</html>