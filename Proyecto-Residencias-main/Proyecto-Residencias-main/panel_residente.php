<?php

session_start();
// Si tienes sesión activa del alumno, puedes tomar sus datos aquí
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
$nombre_estudiante = $_SESSION['nombre_usuario'] ?? 'Misseal Angel Cardenas';
$carrera_estudiante = $_SESSION['carrera'] ?? 'Ing. en Sistemas Computacionales';
$no_control = $_SESSION['no_control'] ?? '20260001';

// Conexión directa (sin 'config/')
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bd_residencias';

$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Configuración de conexión con tu BD actual
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bd_residencias'; // Cambia 'sigeres_db' por el nombre de tu BD

$conexion = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta exacta ajustada a la estructura de tu tabla
$query_descargables = "SELECT 
    id AS id, 
    nombre_archivo AS nombre_documento, 
    tipo_documento AS codigo, 
    ruta_archivo AS tipo_archivo, 
    ruta_archivo AS ruta_archivo, 
    estatus AS estatus 
FROM documentos";

$resultado_descargables = $conexion->query($query_descargables);



// Lista de los 19 documentos del expediente de Residencias Profesionales
$documentos_expediente = [
    'carga_rp'            => 'Carga RP',
    'solicitud_rp'        => 'Solicitud de RP',
    'lib_serv_social'     => 'Lib. Serv. Social',
    'carga_serv_social'   => 'Carga Serv. Social',
    'anteproyecto_firmado'=> 'Anteproyecto Firmado',
    'carta_presentacion'  => 'Carta Presentación',
    'carta_aceptacion'    => 'Carta Aceptación',
    'lib_act_comp'        => 'Lib. Act. Comp.',
    'asig_asesor'         => 'Asig. Asesor',
    'primer_anexo_29'     => '1er Anexo 29',
    'primer_informe_rp'   => '1er Informe RP',
    'segundo_anexo_29'    => '2do Anexo 29',
    'segundo_informe_rp'  => '2do Informe RP',
    'anexo_30'            => 'Anexo 30',
    'tercer_informe_rp'   => '3er Informe RP',
    'informe_tecnico'     => 'Informe Técnico',
    'carta_termino'       => 'Carta Término',
    'portada_firmada'     => 'Portada Firmada',
    'acta_calif'          => 'Acta Calif.',
    'encuesta_sat'        => 'Encuesta Sat.'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - SIGERES</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1a365d;
            --sidebar-active: #2b4c7e;
            --body-bg: #f8fafc;
            --card-border-radius: 12px;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: var(--sidebar-active);
            border-left: 4px solid #38bdf8;
        }

        /* Contenido principal */
        .main-content {
            margin-left: 260px;
            padding: 30px 40px;
        }

        .card-custom {
            border: none;
            border-radius: var(--card-border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            background: white;
        }

        .stat-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .badge-en-curso { background-color: #fef3c7; color: #d97706; }
        .badge-completado { background-color: #d1fae5; color: #059669; }
        .badge-pendiente { background-color: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>

    <!-- Sidebar lateral -->
    <aside class="sidebar">
        <div class="brand">
            <div class="bg-white text-dark fw-bold px-2 py-1 rounded">TecNM</div>
            <div>
                <div class="fw-bold fs-6">SIGERES</div>
                <div style="font-size: 0.7rem; color: #94a3b8;">RESIDENCIAS PROFESIONALES</div>
            </div>
        </div>

        <nav class="nav flex-column">
            <a href="#" class="nav-link active"><i class="bi bi-person-circle"></i> Mi Perfil</a>
            <a href="#seccion-formatos" class="nav-link"><i class="bi bi-file-earmark-text-fill"></i> Mis Formatos (Anexos)</a>
            <a href="#seccion-subir-documentos" class="nav-link"><i class="bi bi-cloud-upload-fill"></i> Subir Documentos</a>
            <a href="#seccion-descargas" class="nav-link"><i class="bi bi-download"></i> Documentos Descargables</a>
        </nav>

        <div class="mt-auto pt-3 border-top border-secondary" style="font-size: 0.85rem; color: #94a3b8;">
            <p class="mb-1"><b>Residente:</b></p>
            <p class="mb-0 text-white text-truncate"><?php echo $nombre_estudiante; ?></p>
        </div>
    </aside>

    <!-- Área de trabajo principal -->
    <main class="main-content">
        
        <!-- Header con bienvenida -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: #0f172a;">Mi Perfil de Residente</h3>
                <p class="text-muted mb-0"><?php echo $carrera_estudiante; ?> | N° Control: <?php echo $no_control; ?></p>
            </div>
            <div>
                <span class="badge-status badge-en-curso">Estatus: Residencia En Curso</span>
            </div>
        </div>

        <!-- Tarjetas resumen de estado -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card card-custom stat-card">
                    <div>
                        <div class="text-muted small fw-bold">SOLICITUD ANEXO 12.4</div>
                        <div class="fs-4 fw-bold text-dark mt-1">Completado</div>
                    </div>
                    <div class="stat-icon" style="background-color: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom stat-card">
                    <div>
                        <div class="text-muted small fw-bold">DICTAMEN ANEXO 29</div>
                        <div class="fs-4 fw-bold text-warning mt-1">Pendiente Llenado</div>
                    </div>
                    <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom stat-card">
                    <div>
                        <div class="text-muted small fw-bold">AVANCE EXPEDIENTE</div>
                        <div class="fs-4 fw-bold text-success mt-1">50%</div>
                    </div>
                    <div class="stat-icon" style="background-color: #d1fae5; color: #059669;">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Formatos interactivos (Anexo 12.4 y Anexo 29) -->
        <div class="row g-4 mb-4" id="seccion-formatos">
            <div class="col-md-12">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3" style="color: #1e293b;">📝 Llenado y Generación de Formatos</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light d-flex flex-column justify-content-between h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary mb-0">Solicitud de Residencia (Anexo 12.4)</h6>
                                        <span class="badge bg-success">Generado</span>
                                    </div>
                                    <p class="small text-muted mb-3">Formato oficial para el registro inicial del proyecto y datos de la empresa.</p>
                                </div>
                                <a href="anexo12_4.php" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-eye"></i> Ver / Llenar Anexo 12.4
                                </a>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light d-flex flex-column justify-content-between h-100" style="border-left: 4px solid #d97706 !important;">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-warning mb-0" style="color: #b45309 !important;">Dictamen de Residencia (Anexo 29)</h6>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    </div>
                                    <p class="small text-muted mb-3">Completa los campos requeridos para la evaluación del dictamen de tu residencia.</p>
                                </div>
                                <a href="anexo29.php" target="_blank" class="btn btn-sm btn-warning text-dark fw-bold w-100">
                                    <i class="bi bi-pencil-fill"></i> Llenar Formato Anexo 29
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tabla: Carga del Expediente completo (19 Documentos) -->
        <div class="card card-custom p-4 mb-4" id="seccion-subir-documentos">
            <h5 class="fw-bold mb-3" style="color: #1e293b;">📤 Expediente Digital de Residencias Profesionales</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 35%;">Requisito Documental</th>
                            <th style="width: 20%;">Estatus</th>
                            <th style="width: 28%;">Seleccionar Archivo (PDF)</th>
                            <th style="width: 12%;" class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach ($documentos_expediente as $clave => $nombre): 
                        ?>
                            <tr>
                                <td><span class="text-muted fw-bold"><?php echo $i++; ?></span></td>
                                <td><b><?php echo $nombre; ?></b></td>
                                <td><span class="badge-status badge-pendiente">Sin Subir</span></td>
                                <td>
                                    <form action="subir_documento.php" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                                        <input type="file" name="archivo_pdf" class="form-control form-control-sm" accept=".pdf" required>
                                </td>
                                <td class="text-end">
                                        <input type="hidden" name="clave_doc" value="<?php echo $clave; ?>">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-upload"></i> Subir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- Tabla de Documentos Descargables -->
<div class="card card-custom p-4 mb-4" id="seccion-descargas">
    <h5 class="fw-bold mb-3" style="color: #1e293b;">📂 Documentos y Archivos Oficiales para Descargar</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Nombre del Documento</th>
                    <th style="width: 18%;">Categoría</th>
                    <th style="width: 15%;">Estatus</th>
                    <th style="width: 27%;" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado_descargables && $resultado_descargables->num_rows > 0): ?>
                    <?php 
                    $num = 1;
                    while ($doc = $resultado_descargables->fetch_assoc()): 
                        // Detectamos si el archivo guardado en la BD es un Word (.doc / .docx)
                        $es_word = (bool)preg_match('/\.(doc|docx)$/i', $doc['ruta_archivo']);
                    ?>
                        <tr>
                            <td><span class="text-muted fw-bold"><?php echo $num++; ?></span></td>
                            <td>
                                <?php if ($es_word): ?>
                                    <i class="bi bi-file-earmark-word text-primary me-2 fs-5"></i>
                                <?php else: ?>
                                    <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5"></i>
                                <?php endif; ?>
                                <b><?php echo htmlspecialchars($doc['nombre_documento']); ?></b>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($doc['codigo'] ?? 'General'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?php echo htmlspecialchars($doc['estatus'] ?? 'Pendiente'); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <!-- Botón Vista Previa -->
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary me-1 btn-preview"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#previewModal"
                                        data-title="<?php echo htmlspecialchars($doc['nombre_documento']); ?>"
                                        data-url="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>"
                                        data-isword="<?php echo $es_word ? 'true' : 'false'; ?>">
                                    <i class="bi bi-eye me-1"></i> Ver
                                </button>

                                <!-- Botón Descargar Directa -->
                                <a href="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>" 
                                    download 
                                    class="btn btn-sm btn-primary shadow-sm">
                                    <i class="bi bi-download me-1"></i> Descargar
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2 text-secondary"></i>
                            No hay documentos disponibles en este momento.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </main>

    <!-- Bootstrap JS -->
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        previewModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const docTitle = button.getAttribute('data-title');
            const docUrl = button.getAttribute('data-url');
            const isWord = button.getAttribute('data-isword') === 'true';

            // Actualizar título del modal y botón de descarga dentro del modal
            document.getElementById('previewModalLabel').innerHTML = `<i class="bi bi-file-earmark-text me-2"></i>${docTitle}`;
            document.getElementById('previewDocTitle').textContent = docTitle;
            document.getElementById('btnModalDownload').setAttribute('href', docUrl);

            const iframe = document.getElementById('previewIframe');

            if (isWord) {
                // Para Word usamos el visor de Microsoft (requiere hosting público)
                const fullUrl = window.location.origin + '/' + docUrl.replace(/^\//, '');
                iframe.src = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fullUrl)}`;
            } else {
                // Para PDF asignamos la ruta directa
                iframe.src = docUrl;
            }
        });

        // Limpiar el iframe al cerrar para liberar memoria
        previewModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('previewIframe').src = '';
        });
    }
</script>
</body>
</html>