<?php
// anexo12_4.php - Formato Oficial TESCHA Versión 2 (19/Nov/2025)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Residencias Profesionales - TESCHA</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; background-color: #e2e8f0; margin: 0; padding: 20px; }
        
        .barra-herramientas {
            max-width: 210mm;
            margin: 0 auto 15px auto;
            background: #1a365d;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-imprimir {
            background-color: #2b6cb0;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .hoja {
            background-color: white;
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 0 auto 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            position: relative;
        }

        .encabezado { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .encabezado td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; font-size: 8pt; }

        .seccion-titulo {
            background-color: #f1f5f9;
            font-weight: bold;
            padding: 3px 6px;
            border: 1px solid #000;
            margin-top: 8px;
            font-size: 9pt;
        }

        .tabla-datos { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .tabla-datos td { border: 1px solid #000; padding: 3px 5px; font-size: 9pt; vertical-align: middle; }

        input[type="text"], input[type="email"], select, textarea {
            width: 100%;
            border: none;
            border-bottom: 1px dashed #718096;
            background: #f7fafc;
            font-family: inherit;
            font-size: 9pt;
            padding: 2px;
            outline: none;
        }

        input:focus, select:focus, textarea:focus { background-color: #ebf8ff; border-bottom: 1px solid #3182ce; }

        .pie-pagina {
            position: absolute;
            bottom: 10mm;
            left: 15mm;
            right: 15mm;
            text-align: center;
            font-size: 7.5pt;
            color: #4a5568;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        @media print {
            body { background: white; padding: 0; }
            .barra-herramientas { display: none !important; }
            .hoja { box-shadow: none; margin: 0; width: 100%; padding: 15mm; page-break-after: always; }
            input[type="text"], input[type="email"], select, textarea {
                border: none !important;
                background: transparent !important;
                padding: 0 !important;
                appearance: none;
            }
        }
    </style>
</head>
<body>

    <div class="barra-herramientas">
        <span><b>SigeRes:</b> Solicitud de Residencias Profesionales (TESCHA)</span>
        <button class="btn-imprimir" onclick="window.print()">🖨️ Guardar en PDF / Imprimir</button>
    </div>

    <!-- PÁGINA 1 -->
    <div class="hoja">
        <table class="encabezado">
            <tr>
                <td style="width: 25%;"><b>TECNOLÓGICO DE ESTUDIOS SUPERIORES DE CHALCO</b></td>
                <td style="width: 50%;"><b>NOMBRE DEL FORMATO:</b><br>Solicitud de Residencias Profesionales</td>
                <td style="width: 25%;">
                    Página: 1 de 4<br>
                    Versión: 2<br>
                    Fecha: 19/Nov/2025
                </td>
            </tr>
        </table>

        <div style="text-align: right; margin: 10px 0;">
            Lugar y Fecha: <input type="text" style="width: 250px;" value="Chalco, Mex. a <?php echo date('d/m/Y'); ?>">
        </div>

        <p style="margin: 3px 0;"><b>C.</b> <input type="text" style="width: 70%;" placeholder="Nombre del Jefe(a) de la Div. de Estudios Profesionales"></p>
        <p style="margin: 0 0 5px 0; font-size: 8.5pt;">Jefe (a) de la Div. de Estudios Profesionales</p>

        <p style="margin: 3px 0;"><b>AT'N: C.</b> <input type="text" style="width: 65%;" placeholder="Nombre del Coord. de la Carrera"></p>
        <p style="margin: 0 0 10px 0; font-size: 8.5pt;">Coord. de la Carrera de: <input type="text" style="width: 50%;" value="Ingeniería en Sistemas Computacionales"></p>

        <table class="tabla-datos">
            <tr>
                <td colspan="2"><span style="font-weight:bold;">NOMBRE DEL PROYECTO:</span> <input type="text" placeholder="Nombre completo del proyecto"></td>
            </tr>
            <tr>
                <td>
                    <b>OPCIÓN ELEGIDA:</b><br>
                    <label><input type="radio" name="opcion" value="Banco"> Banco de Proyectos</label><br>
                    <label><input type="radio" name="opcion" value="Propuesta"> Propuesta propia</label><br>
                    <label><input type="radio" name="opcion" value="Trabajador"> Trabajador (a)</label>
                </td>
                <td style="vertical-align: top;">
                    <b>PERIODO PROYECTADO:</b><input type="text" placeholder="Ej. Agosto - Diciembre 2026"><br><br>
                    <b>Número de Residentes:</b> <input type="text" value="1" style="width: 40px;">
                </td>
            </tr>
        </table>

        <div class="seccion-titulo">Datos de la empresa:</div>
        <table class="tabla-datos">
            <tr>
                <td style="width: 60%;"><b>Nombre:</b> <input type="text" placeholder="Razón Social"></td>
                <td style="width: 40%;"><b>R.F.C.:</b> <input type="text" placeholder="RFC"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>Giro, Ramo o Sector:</b> 
                    <label><input type="checkbox"> Industrial</label> | 
                    <label><input type="checkbox"> Servicios</label> | 
                    <label><input type="checkbox"> Otro</label> &nbsp;&nbsp;&nbsp;
                    <label><input type="checkbox"> Público</label> | 
                    <label><input type="checkbox"> Privado</label>
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>Domicilio:</b> <input type="text" placeholder="Calle y número"></td>
            </tr>
            <tr>
                <td><b>Colonia:</b> <input type="text"></td>
                <td><b>C.P.:</b> <input type="text"></td>
            </tr>
            <tr>
                <td><b>Ciudad:</b> <input type="text"></td>
                <td><b>Fax:</b> <input type="text"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Teléfono (no celular):</b> <input type="text"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Misión de la Empresa:</b><br><textarea rows="2"></textarea></td>
            </tr>
            <tr>
                <td><b>Nombre del (a) Titular:</b> <input type="text"></td>
                <td><b>Puesto:</b> <input type="text"></td>
            </tr>
            <tr>
                <td><b>Nombre del (a) Asesor (a) Externo:</b> <input type="text"></td>
                <td><b>Puesto:</b> <input type="text"></td>
            </tr>
            <tr>
                <td><b>Persona que firmará convenio:</b> <input type="text"></td>
                <td><b>Puesto:</b> <input type="text"></td>
            </tr>
        </table>

        <div class="pie-pagina">
            Toda copia en Papel es un "Documento No Controlado" a excepción del original<br>
            Carretera Federal México-Cuautla s/n La Candelaria Tlapala C.P. 56641, Chalco, Estado de México.
        </div>
    </div>

    <!-- PÁGINA 2 -->
    <div class="hoja">
        <table class="encabezado">
            <tr>
                <td style="width: 25%;"><b>TECNOLÓGICO DE ESTUDIOS SUPERIORES DE CHALCO</b></td>
                <td style="width: 50%;"><b>NOMBRE DEL FORMATO:</b><br>Solicitud de Residencias Profesionales</td>
                <td style="width: 25%;">Página: 2 de 4<br>Versión: 2<br>Fecha: 19/Nov/2025</td>
            </tr>
        </table>

        <div class="seccion-titulo">Datos del (a) Residente:</div>
        <table class="tabla-datos">
            <tr>
                <td colspan="2"><b>Nombre:</b> <input type="text" id="nombre_alumno" oninput="actualizarFirma(this.value)"></td>
            </tr>
            <tr>
                <td><b>Carrera:</b> <input type="text" value="Ingeniería en Sistemas Computacionales"></td>
                <td><b>No. de control:</b> <input type="text"></td>
            </tr>
            <tr>
                <td colspan="2"><b>Domicilio:</b> <input type="text"></td>
            </tr>
            <tr>
                <td><b>E-mail:</b> <input type="email"></td>
                <td>
                    <b>Para Seguridad Social acudir:</b><br>
                    <label><input type="checkbox"> IMSS</label> 
                    <label><input type="checkbox"> ISSSTE</label> 
                    <label><input type="checkbox"> OTROS</label><br>
                    <b>No.:</b> <input type="text">
                </td>
            </tr>
            <tr>
                <td><b>Ciudad:</b> <input type="text"></td>
                <td><b>Teléfono (no celular):</b> <input type="text"></td>
            </tr>
        </table>

        <div style="margin-top: 80px; text-align: center;">
            <div style="border-top: 1px solid #000; width: 60%; margin: 0 auto; padding-top: 5px;">
                <span id="txt_firma">Firma del (a) Estudiante</span>
            </div>
        </div>

        <div class="pie-pagina">
            Toda copia en Papel es un "Documento No Controlado" a excepción del original<br>
            Carretera Federal México-Cuautla s/n La Candelaria Tlapala C.P. 56641, Chalco, Estado de México.
        </div>
    </div>

    <script>
        function actualizarFirma(val) {
            document.getElementById('txt_firma').innerText = val ? val : 'Firma del (a) Estudiante';
        }
    </script>
</body>
</html>