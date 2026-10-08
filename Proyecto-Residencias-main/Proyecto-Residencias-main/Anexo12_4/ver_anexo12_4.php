<?php
// ver_anexo12_4.php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: llenar_anexo12_4.php");
    exit();
}

$d = $_POST; // Datos recibidos del formulario
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Anexo_12_4_<?php echo htmlspecialchars($d['num_control']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; background: #e2e8f0; margin: 0; padding: 20px; }
        
        .barra { max-width: 210mm; margin: 0 auto 15px auto; text-align: right; }
        .btn { padding: 10px 20px; background-color: #1a365d; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }

        /* Estructura Exacta Hoja Carta (A4) */
        .hoja {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            box-sizing: border-box;
        }

        .encabezado-tabla { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .encabezado-tabla td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }

        .seccion-titulo { background-color: #f1f5f9; font-weight: bold; padding: 4px; border: 1px solid #000; margin-top: 10px; }
        .tabla-datos { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .tabla-datos td { border: 1px solid #000; padding: 4px 6px; font-size: 10pt; }
        .label { font-weight: bold; }

        .firmas { margin-top: 50px; width: 100%; text-align: center; }
        .firmas td { width: 50%; vertical-align: bottom; height: 70px; }
        .linea-firma { border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 5px; font-weight: bold; }

        @media print {
            body { background: white; padding: 0; }
            .barra { display: none; }
            .hoja { box-shadow: none; margin: 0; width: 100%; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="barra">
        <button class="btn" onclick="window.print()">🖨️ Imprimir / Guardar en PDF</button>
    </div>

    <div class="hoja">
        
        <!-- ENCABEZADO OFICIAL -->
        <table class="encabezado-tabla">
            <tr>
                <td style="width: 20%;"><b>TecNM</b></td>
                <td style="width: 60%;">
                    <b>TECNOLÓGICO NACIONAL DE MÉXICO</b><br>
                    <span>INSTITUTO TECNOLÓGICO / TESCH</span><br>
                    <b>SOLICITUD DE RESIDENCIA PROFESIONAL</b>
                </td>
                <td style="width: 20%; font-size: 8pt;">
                    <b>Código:</b> TecNM-AC-PO-007-01<br>
                    <b>Anexo:</b> 12.4
                </td>
            </tr>
        </table>

        <p style="text-align: right; margin-bottom: 15px;"><b>Lugar y Fecha:</b> <?php echo htmlspecialchars($d['lugar_fecha']); ?></p>

        <p><b>AT’N: </b><?php echo htmlspecialchars($d['jefe_vinculacion']); ?><br>
        Jefe del Depto. de Gestión Tecnológica y Vinculación.</p>

        <p>Por medio de la presente solicito sea aceptado mi proyecto de Residencia Profesional:</p>

        <!-- 1. DATOS DEL PROYECTO -->
        <div class="seccion-titulo">1. DATOS DEL PROYECTO</div>
        <table class="tabla-datos">
            <tr>
                <td colspan="2"><span class="label">Nombre del Proyecto:</span> <?php echo htmlspecialchars($d['nombre_proyecto']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Opción Elegida:</span> <?php echo htmlspecialchars($d['opcion_elegida']); ?></td>
                <td><span class="label">Periodo:</span> <?php echo htmlspecialchars($d['periodo']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Número de Residentes:</span> <?php echo htmlspecialchars($d['num_residentes']); ?></td>
            </tr>
        </table>

        <!-- 2. DATOS DE LA EMPRESA -->
        <div class="seccion-titulo">2. DATOS DE LA EMPRESA</div>
        <table class="tabla-datos">
            <tr>
                <td><span class="label">Nombre / Razón Social:</span> <?php echo htmlspecialchars($d['empresa']); ?></td>
                <td><span class="label">RFC:</span> <?php echo htmlspecialchars($d['rfc_empresa']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Ramo:</span> <?php echo htmlspecialchars($d['ramo']); ?></td>
                <td><span class="label">Giro:</span> <?php echo htmlspecialchars($d['giro']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Domicilio:</span> <?php echo htmlspecialchars($d['domicilio_empresa']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Asesor Externo:</span> <?php echo htmlspecialchars($d['asesor_externo']); ?></td>
                <td><span class="label">Puesto:</span> <?php echo htmlspecialchars($d['puesto_asesor_externo']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Titular de la Empresa:</span> <?php echo htmlspecialchars($d['titular_empresa']); ?></td>
                <td><span class="label">Puesto:</span> <?php echo htmlspecialchars($d['puesto_titular']); ?></td>
            </tr>
        </table>

        <!-- 3. DATOS DEL RESIDENTE -->
        <div class="seccion-titulo">3. DATOS DEL RESIDENTE</div>
        <table class="tabla-datos">
            <tr>
                <td colspan="2"><span class="label">Nombre:</span> <?php echo htmlspecialchars($d['nombre_alumno']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Carrera:</span> <?php echo htmlspecialchars($d['carrera']); ?></td>
                <td><span class="label">No. de Control:</span> <?php echo htmlspecialchars($d['num_control']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Domicilio:</span> <?php echo htmlspecialchars($d['domicilio_alumno']); ?></td>
            </tr>
            <tr>
                <td><span class="label">Teléfono:</span> <?php echo htmlspecialchars($d['telefono']); ?></td>
                <td><span class="label">E-mail:</span> <?php echo htmlspecialchars($d['correo']); ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Seguridad Social:</span> <?php echo htmlspecialchars($d['seguro_social']); ?></td>
            </tr>
        </table>

        <!-- FIRMAS -->
        <table class="firmas">
            <tr>
                <td>
                    <div class="linea-firma">
                        <?php echo htmlspecialchars($d['nombre_alumno']); ?><br>
                        <span>Firma del Alumno</span>
                    </div>
                </td>
                <td>
                    <div class="linea-firma">
                        <?php echo htmlspecialchars($d['coordinador']); ?><br>
                        <span>Coordinador(a) de Carrera</span>
                    </div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>