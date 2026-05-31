<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

$resultado = mysqli_query($conexion,
    "SELECT nombre, apellido, carrera, nro_matricula, email, telefono, fecha_solicitud
     FROM egresados WHERE estado = 'aprobado' ORDER BY apellido ASC"
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Egresados</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; }
        nav { margin-bottom: 24px; }
        nav a { margin-right: 16px; color: #2c3e50; text-decoration: none; font-weight: bold; }
        nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .sin-egresados { color: #7f8c8d; font-style: italic; margin-top: 20px; }
    </style>
</head>
<body>

    <h1>Listado de Egresados</h1>

    <nav>
        <a href="panel.php">Solicitudes pendientes</a>
        <a href="egresados.php">Listado de egresados</a>
        <a href="cambiar_pass.php">Cambiar contraseña</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>

    <?php if (mysqli_num_rows($resultado) === 0): ?>
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
            <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
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
