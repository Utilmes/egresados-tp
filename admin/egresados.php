<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$aprobados = mysqli_query($conexion,
    "SELECT nombre, apellido, carrera, nro_matricula, email, telefono, fecha_solicitud
     FROM egresados WHERE estado = 'aprobado' ORDER BY apellido ASC"
);

$rechazados = mysqli_query($conexion,
    "SELECT nombre, apellido, carrera, nro_matricula, email, telefono, fecha_solicitud
     FROM egresados WHERE estado = 'rechazado' ORDER BY apellido ASC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Egresados</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h1, h2 { color: #2c3e50; }
        nav { margin-bottom: 24px; overflow: hidden; }
        nav a { margin-right: 16px; color: #2c3e50; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        nav span { float: right; color: #7f8c8d; font-size: 0.9em; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .th-rechazado { background-color: #7f8c8d; }
        .sin-egresados { color: #7f8c8d; font-style: italic; margin-top: 20px; }
    </style>
</head>
<body>

    <h1>Listado de Egresados</h1>

    <nav>
        <a href="../index.php">← Página pública</a>
        <a href="panel.php">Solicitudes pendientes</a>
        <a href="egresados.php">Listado de egresados</a>
        <a href="cambiar_pass.php">Cambiar contraseña</a>
        <a href="logout.php">Cerrar sesión</a>
        <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_usuario']); ?></span>
    </nav>

    <h2>Aprobados</h2>

    <?php if (mysqli_num_rows($aprobados) === 0): ?>
        <p class="sin-egresados">No hay egresados aprobados aún.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Carrera</th>
                <th>Matrícula</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Fecha aprobación</th>
            </tr>
            <?php while ($fila = mysqli_fetch_assoc($aprobados)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['carrera']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nro_matricula']); ?></td>
                    <td><?php echo htmlspecialchars($fila['email']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_solicitud']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <h2>Rechazados</h2>

    <?php if (mysqli_num_rows($rechazados) === 0): ?>
        <p class="sin-egresados">No hay solicitudes rechazadas.</p>
    <?php else: ?>
        <table>
            <tr>
                <th class="th-rechazado">Nombre</th>
                <th class="th-rechazado">Apellido</th>
                <th class="th-rechazado">Carrera</th>
                <th class="th-rechazado">Matrícula</th>
                <th class="th-rechazado">Email</th>
                <th class="th-rechazado">Teléfono</th>
                <th class="th-rechazado">Fecha solicitud</th>
            </tr>
            <?php while ($fila = mysqli_fetch_assoc($rechazados)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($fila['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($fila['carrera']); ?></td>
                    <td><?php echo htmlspecialchars($fila['nro_matricula']); ?></td>
                    <td><?php echo htmlspecialchars($fila['email']); ?></td>
                    <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($fila['fecha_solicitud']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

</body>
</html>
