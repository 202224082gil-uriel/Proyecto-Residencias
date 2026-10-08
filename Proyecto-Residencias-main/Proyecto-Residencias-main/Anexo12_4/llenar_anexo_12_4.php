<!-- llenar_anexo12_4.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SigeRes - Anexo 12.4 Solicitud de Residencia</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        .container { max-width: 800px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #1a365d; }
        fieldset { border: 1px solid #cbd5e1; margin-bottom: 20px; padding: 15px; border-radius: 5px; }
        legend { font-weight: bold; color: #1a365d; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; }
        input[type="text"], input[type="date"], input[type="email"], select, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #1a365d; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background-color: #2b6cb0; }
    </style>
</head>
<body>

<div class="container">
    <h2>SOLICITUD DE RESIDENCIA PROFESIONAL (ANEXO 12.4)</h2>
    
    <form action="ver_anexo12_4.php" method="POST">
        
        <fieldset>
            <legend>1. Datos Generales</legend>
            <div class="form-group">
                <label>Lugar y Fecha:</label>
                <input type="text" name="lugar_fecha" value="Chalco, Estado de México a <?php echo date('d/m/Y'); ?>" required>
            </div>
            <div class="form-group">
                <label>Nombre del Jefe del Depto. de Gestión Tecnológica y Vinculación:</label>
                <input type="text" name="jefe_vinculacion" required>
            </div>
            <div class="form-group">
                <label>Coordinador(a) de la Carrera:</label>
                <input type="text" name="coordinador" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>2. Datos del Proyecto</legend>
            <div class="form-group">
                <label>Nombre del Proyecto:</label>
                <input type="text" name="nombre_proyecto" required>
            </div>
            <div class="form-group">
                <label>Opción Elegida:</label>
                <select name="opcion_elegida">
                    <option value="Banco de Proyectos">Banco de Proyectos</option>
                    <option value="Propuesta Propia">Propuesta Propia</option>
                    <option value="Trabajador">Trabajador</option>
                </select>
            </div>
            <div class="form-group">
                <label>Periodo Proyectado:</label>
                <input type="text" name="periodo" placeholder="Ej. Agosto - Diciembre 2026" required>
            </div>
            <div class="form-group">
                <label>Número de Residentes:</label>
                <input type="text" name="num_residentes" value="1" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>3. Datos de la Empresa</legend>
            <div class="form-group">
                <label>Nombre Comercial / Razón Social:</label>
                <input type="text" name="empresa" required>
            </div>
            <div class="form-group">
                <label>RFC de la Empresa:</label>
                <input type="text" name="rfc_empresa">
            </div>
            <div class="form-group">
                <label>Ramo / Sector:</label>
                <input type="text" name="ramo" placeholder="Ej. Industrial, Servicios, Público">
            </div>
            <div class="form-group">
                <label>Giro de la Empresa:</label>
                <input type="text" name="giro">
            </div>
            <div class="form-group">
                <label>Domicilio (Calle, No., Col., C.P., Ciudad):</label>
                <input type="text" name="domicilio_empresa" required>
            </div>
            <div class="form-group">
                <label>Nombre del Asesor Externo:</label>
                <input type="text" name="asesor_externo" required>
            </div>
            <div class="form-group">
                <label>Puesto del Asesor Externo:</label>
                <input type="text" name="puesto_asesor_externo">
            </div>
            <div class="form-group">
                <label>Nombre del Titular de la Empresa:</label>
                <input type="text" name="titular_empresa" required>
            </div>
            <div class="form-group">
                <label>Puesto del Titular:</label>
                <input type="text" name="puesto_titular">
            </div>
        </fieldset>

        <fieldset>
            <legend>4. Datos del Residente</legend>
            <div class="form-group">
                <label>Nombre Completo:</label>
                <input type="text" name="nombre_alumno" required>
            </div>
            <div class="form-group">
                <label>Carrera:</label>
                <input type="text" name="carrera" value="Ingeniería en Sistemas Computacionales" required>
            </div>
            <div class="form-group">
                <label>Número de Control:</label>
                <input type="text" name="num_control" required>
            </div>
            <div class="form-group">
                <label>Domicilio Particular:</label>
                <input type="text" name="domicilio_alumno" required>
            </div>
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico Institutional:</label>
                <input type="email" name="correo" required>
            </div>
            <div class="form-group">
                <label>Para Seguridad Social (IMSS / ISSSTE):</label>
                <input type="text" name="seguro_social" placeholder="Ej. NÚMERO DE NSS: 1234567890" required>
            </div>
        </fieldset>

        <button type="submit">Generar Anexo 12.4 para Imprimir / Guardar</button>
    </form>
</div>

</body>
</html>