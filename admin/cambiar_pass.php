<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$errores = [];
$exito   = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass_actual   = $_POST['pass_actual'] ?? '';
    $pass_nueva    = $_POST['pass_nueva'] ?? '';
    $pass_confirm  = $_POST['pass_confirm'] ?? '';

    if ($pass_actual === '' || $pass_nueva === '' || $pass_confirm === '') {
        $errores[] = "Todos los campos son obligatorios.";
    } elseif ($pass_nueva !== $pass_confirm) {
        $errores[] = "La nueva contraseña y la confirmación no coinciden.";
    } elseif (strlen($pass_nueva) < 6) {
        $errores[] = "La nueva contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt = mysqli_prepare($conexion, "SELECT password FROM administradores WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $_SESSION['admin_id']);
        mysqli_stmt_execute($stmt);
        $res   = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if (!$admin || !password_verify($pass_actual, $admin['password'])) {
            $errores[] = "La contraseña actual es incorrecta.";
        } else {
            $nuevo_hash = password_hash($pass_nueva, PASSWORD_DEFAULT);
            $stmt2 = mysqli_prepare($conexion, "UPDATE administradores SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt2, 'si', $nuevo_hash, $_SESSION['admin_id']);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
            $exito = true;
        }
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; }
        nav { margin-bottom: 24px; }
        nav a { margin-right: 16px; color: #2c3e50; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #34495e; }
        .errores { background: #fdecea; border: 1px solid #e74c3c; padding: 10px; border-radius: 4px; color: #c0392b; margin-bottom: 16px; }
        .errores li { margin: 4px 0; }
        .exito { background: #eafaf1; border: 1px solid #2ecc71; padding: 16px; border-radius: 4px; color: #1e8449; }
    </style>
</head>
<body>

    <h1>Cambiar Contraseña</h1>

    <nav>
        <a href="panel.php">Solicitudes pendientes</a>
        <a href="egresados.php">Listado de egresados</a>
        <a href="cambiar_pass.php">Cambiar contraseña</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>

    <?php if ($exito): ?>
        <div class="exito">Contraseña actualizada correctamente.</div>
    <?php else: ?>

        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul>
                    <?php foreach ($errores as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="cambiar_pass.php">
            <label for="pass_actual">Contraseña actual</label>
            <input type="password" id="pass_actual" name="pass_actual" required>

            <label for="pass_nueva">Nueva contraseña</label>
            <input type="password" id="pass_nueva" name="pass_nueva" required>

            <label for="pass_confirm">Confirmar nueva contraseña</label>
            <input type="password" id="pass_confirm" name="pass_confirm" required>

            <button type="submit">Actualizar contraseña</button>
        </form>

    <?php endif; ?>

</body>
</html>
