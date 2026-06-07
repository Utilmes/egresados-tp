<?php
require_once 'includes/db.php';

$carreras = [
    "Tecnicatura Universitaria en Programación",
    "Ingeniería en Sistemas de Información",
    "Licenciatura en Sistemas",
    "Administración de Empresas",
    "Contabilidad",
    "Diseño Gráfico",
    "Ciencias Económicas",
];

$errores  = [];
$exito    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim(isset($_POST['nombre'])       ? $_POST['nombre']       : '');
    $apellido     = trim(isset($_POST['apellido'])     ? $_POST['apellido']     : '');
    $carrera      = trim(isset($_POST['carrera'])      ? $_POST['carrera']      : '');
    $nro_matricula = trim(isset($_POST['nro_matricula']) ? $_POST['nro_matricula'] : '');
    $email        = trim(isset($_POST['email'])        ? $_POST['email']        : '');
    $telefono     = trim(isset($_POST['telefono'])     ? $_POST['telefono']     : '');

    if ($nombre === '')        $errores[] = "El nombre es obligatorio.";
    if ($apellido === '')      $errores[] = "El apellido es obligatorio.";
    if ($carrera === '')       $errores[] = "Debe seleccionar una carrera.";
    if ($nro_matricula === '') $errores[] = "El número de matrícula es obligatorio.";
    if ($email === '')         $errores[] = "El email es obligatorio.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "El formato del email no es válido.";
    if ($telefono === '')      $errores[] = "El teléfono es obligatorio.";

    if (!in_array($carrera, $carreras)) {
        $errores[] = "La carrera seleccionada no es válida.";
    }

    if (empty($errores)) {
        $stmt = mysqli_prepare($conexion,
            "INSERT INTO egresados (nombre, apellido, carrera, nro_matricula, email, telefono, estado)
             VALUES (?, ?, ?, ?, ?, ?, 'pendiente')"
        );
        mysqli_stmt_bind_param($stmt, 'ssssss', $nombre, $apellido, $carrera, $nro_matricula, $email, $telefono);

        if (mysqli_stmt_execute($stmt)) {
            $exito = true;
        } else {
            $errores[] = "Ocurrió un error al registrar la solicitud. Intente nuevamente.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Egresados</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #2c3e50; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #34495e; }
        .errores { background: #fdecea; border: 1px solid #e74c3c; padding: 10px; border-radius: 4px; margin-bottom: 16px; color: #c0392b; }
        .errores li { margin: 4px 0; }
        .exito { background: #eafaf1; border: 1px solid #2ecc71; padding: 16px; border-radius: 4px; color: #1e8449; }
    </style>
</head>
<body>

    <h1>Registro de Egresados</h1>
    <p>Completá el formulario para registrar tu solicitud de alta como egresado.</p>

    <?php if ($exito): ?>
        <div class="exito">
            <strong>¡Solicitud enviada correctamente!</strong><br>
            Tu solicitud quedó registrada y será revisada por un administrador.
        </div>
    <?php else: ?>

        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php">

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required
                   value="<?php echo htmlspecialchars(isset($_POST['nombre']) ? $_POST['nombre'] : ''); ?>">

            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" required
                   value="<?php echo htmlspecialchars(isset($_POST['apellido']) ? $_POST['apellido'] : ''); ?>">

            <label for="carrera">Carrera</label>
            <select id="carrera" name="carrera" required>
                <option value="">-- Seleccioná tu carrera --</option>
                <?php foreach ($carreras as $opcion): ?>
                    <option value="<?php echo htmlspecialchars($opcion); ?>"
                        <?php echo ((isset($_POST['carrera']) ? $_POST['carrera'] : '') === $opcion) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($opcion); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nro_matricula">Nro. de Matrícula</label>
            <input type="text" id="nro_matricula" name="nro_matricula" required
                   value="<?php echo htmlspecialchars(isset($_POST['nro_matricula']) ? $_POST['nro_matricula'] : ''); ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required
                   value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : ''); ?>">

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" required
                   value="<?php echo htmlspecialchars(isset($_POST['telefono']) ? $_POST['telefono'] : ''); ?>">

            <button type="submit">Enviar solicitud</button>

        </form>

    <?php endif; ?>

</body>
</html>
