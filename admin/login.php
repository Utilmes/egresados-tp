<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: panel.php');
    exit;
}

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim(isset($_POST['usuario'])  ? $_POST['usuario']  : '');
    $clave   = trim(isset($_POST['password']) ? $_POST['password'] : '');

    if ($usuario === '' || $clave === '') {
        $error = "Completá usuario y contraseña.";
    } else {
        $consulta = mysqli_prepare($conexion, "SELECT id, password FROM administradores WHERE usuario = ?");
        mysqli_stmt_bind_param($consulta, 's', $usuario);
        mysqli_stmt_execute($consulta);
        $resultado = mysqli_stmt_get_result($consulta);
        $admin = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($consulta);

        if ($admin && password_verify($clave, $admin['password'])) {
            $_SESSION['admin_id']      = $admin['id'];
            $_SESSION['admin_usuario'] = $usuario;
            header('Location: panel.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Administración</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 80px auto; padding: 0 20px; }
        h1 { color: #2c3e50; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background-color: #34495e; }
        .error { background: #fdecea; border: 1px solid #e74c3c; padding: 10px; border-radius: 4px; color: #c0392b; margin-bottom: 16px; }
    </style>
</head>
<body>

    <h1>Panel de Administración</h1>

    <?php if ($error !== ''): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required autocomplete="username">

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit">Ingresar</button>
    </form>

</body>
</html>
