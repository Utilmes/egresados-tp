<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$aviso = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';

$resultado = mysqli_query($conexion,
    "SELECT id, nombre, apellido, carrera, nro_matricula, email, telefono, fecha_solicitud
     FROM egresados WHERE estado = 'pendiente' ORDER BY fecha_solicitud ASC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; }
        nav { margin-bottom: 24px; overflow: hidden; }
        nav a { margin-right: 16px; color: #2c3e50; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        nav span { float: right; color: #7f8c8d; font-size: 0.9em; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-aprobar { background: #27ae60; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .btn-rechazar { background: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; margin-left: 6px; }
        .sin-solicitudes { color: #7f8c8d; font-style: italic; margin-top: 20px; }
        .exito { background: #eafaf1; border: 1px solid #2ecc71; padding: 12px; border-radius: 4px; color: #1e8449; margin-bottom: 16px; }
        .aviso { background: #fef9e7; border: 1px solid #f39c12; padding: 12px; border-radius: 4px; color: #9a6000; margin-bottom: 16px; }
    </style>
</head>
<body>

    <h1>Solicitudes Pendientes</h1>

    <nav>
        <a href="../index.php">← Página pública</a>
        <a href="panel.php">Solicitudes pendientes</a>
        <a href="egresados.php">Listado de egresados</a>
        <a href="cambiar_pass.php">Cambiar contraseña</a>
        <a href="logout.php">Cerrar sesión</a>
        <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_usuario']); ?></span>
    </nav>

    <?php if ($aviso === 'aprobado'): ?>
        <div class="exito">La solicitud fue aprobada correctamente.</div>
    <?php elseif ($aviso === 'rechazado'): ?>
        <div class="aviso">La solicitud fue rechazada.</div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($resultado) === 0): ?>
        <p class="sin-solicitudes">No hay solicitudes pendientes.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Carrera</th>
                <th>Matrícula</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['carrera']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nro_matricula']); ?></td>
                    <td><?php echo htmlspecialchars($fila['email']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_solicitud']); ?></td>
                    <td>
                        <form method="POST" action="accion.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo (int)$fila['id']; ?>">
                            <button type="submit" name="accion" value="aprobar" class="btn-aprobar"
                                onclick="return confirm('¿Aprobar la solicitud de <?php echo htmlspecialchars($fila['nombre']); ?>?')">Aprobar</button>
                            <button type="submit" name="accion" value="rechazar" class="btn-rechazar"
                                onclick="return confirm('¿Rechazar la solicitud de <?php echo htmlspecialchars($fila['nombre']); ?>?')">Rechazar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

</body>
</html>
