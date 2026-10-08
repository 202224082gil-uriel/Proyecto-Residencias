<?php
session_start();
include 'conexion.php';

// Si ya hay sesión activa, redirigir según el rol
if (isset($_SESSION['usuario_id'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: panel_admin.php");
    } else {
        echo "✅ LOGIN EXITOSO. Sesión creada correctamente.";
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $stmt = $conexion->prepare("SELECT id, correo, password, nombre_completo, rol FROM usuarios WHERE correo = ? AND activo = 1");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['correo']     = $user['correo'];
                $_SESSION['nombre']     = $user['nombre_completo'];
                $_SESSION['rol']        = $user['rol'];

                if ($user['rol'] === 'admin') {
                    header("Location: panel_admin.php");
                } else {
                    header("Location: mi_perfil.php");
                }
                exit();
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado o inactivo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SIGERES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Open+Sans:wght@400;600&display=swap');
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-200">
        <div class="text-center mb-6">
            <div class="inline-block bg-blue-900 px-4 py-2 rounded-lg mb-3 shadow-md">
                <span class="font-black text-white text-2xl tracking-tighter">TecNM</span>
            </div>
            <h1 class="text-2xl font-black text-blue-900">SIGERES</h1>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">
                Gestión de Residencias Profesionales
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold p-3 rounded-lg mb-4 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Correo Institucional</label>
                <input type="email" name="correo" required autofocus
                       placeholder="usuario@tesch.edu.mx"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-900">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Contraseña</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-900">
            </div>

            <button type="submit"
                    class="w-full bg-blue-900 hover:bg-blue-800 text-white font-bold py-2.5 rounded-lg text-sm transition-colors shadow-md">
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <p class="text-[10px] text-slate-400 uppercase tracking-wider">
                Depto. de Residencias Profesionales · TecNM Chalco
            </p>
        </div>
    </div>

</body>
</html>