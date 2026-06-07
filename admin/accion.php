<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: panel.php');
    exit;
}

$id     = (int)(isset($_POST['id'])     ? $_POST['id']     : 0);
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($id <= 0 || !in_array($accion, ['aprobar', 'rechazar'])) {
    header('Location: panel.php');
    exit;
}

$estado = ($accion === 'aprobar') ? 'aprobado' : 'rechazado';

$consulta = mysqli_prepare($conexion, "UPDATE egresados SET estado = ? WHERE id = ? AND estado = 'pendiente'");
mysqli_stmt_bind_param($consulta, 'si', $estado, $id);
mysqli_stmt_execute($consulta);
mysqli_stmt_close($consulta);

if ($accion === 'aprobar') {
    $consulta2 = mysqli_prepare($conexion, "SELECT nombre, apellido, email FROM egresados WHERE id = ?");
    mysqli_stmt_bind_param($consulta2, 'i', $id);
    mysqli_stmt_execute($consulta2);
    $resultado = mysqli_stmt_get_result($consulta2);
    $egresado  = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($consulta2);

    if ($egresado) {
        $destinatario = $egresado['email'];
        $asunto       = "Tu solicitud de egresado fue aprobada";
        $cabeceras    = "From: universidad@ejemplo.com\r\nContent-type: text/html; charset=utf-8";
        $cuerpo       = "
            <html>
            <body>
                <p>Estimado/a <strong>" . htmlspecialchars($egresado['nombre']) . " " . htmlspecialchars($egresado['apellido']) . "</strong>,</p>
                <p>Tu solicitud de registro como egresado ha sido <strong>aprobada</strong>.</p>
                <p>Bienvenido/a a la comunidad de egresados.</p>
            </body>
            </html>
        ";
        mail($destinatario, $asunto, $cuerpo, $cabeceras);
    }
}

mysqli_close($conexion);
header('Location: panel.php?mensaje=' . $estado);
exit;
