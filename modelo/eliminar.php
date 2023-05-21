<?php
session_start();

// Verificar si el usuario está logeado
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}

$id = $_SESSION['id'];

// Verificar si se proporcionó un ID válido para eliminar el registro
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('sapo!');window.location.href='ajustes.php';</script>"; 
    exit;
}


$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';

// Conectar a la base de datos
$conexion = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('No se pudo conectar al servidor: ' . mysqli_connect_error());
}

// Obtener el nombre de usuario antes del borrado
$idUsuario = $_GET['id'];
$nombreUser = '';

if ($stmt = $conexion->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $stmt->bind_result($nombreUser);
    $stmt->fetch();
    $stmt->close();
}

// Preparar y ejecutar la consulta para eliminar el registro
$stmt = $conexion->prepare('DELETE FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$stmt->close();

// Obtener el nombre de usuario del administrador que realiza los cambios
$adminNombreUsuario = $_SESSION['name'];

// Construir la descripción para el registro de auditoría
$descripcionCompleta = $adminNombreUsuario . ' eliminó los datos de ' . $nombreUser;

// Insertar el registro de auditoría
registrarAuditoria('Borrado de datos', $descripcionCompleta, $id, $adminNombreUsuario);

// Redireccionar a la página de ajustes después de eliminar el registro
echo "<script>alert('Borrado exitoso!');window.location='ajustes.php';</script>";
exit;

function registrarAuditoria($accion, $descripcion, $id, $nombreUser)
{
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'Colectivo';

    // Conectar a la base de datos
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('No se pudo conectar al servidor: ' . mysqli_connect_error());
    }

    // Insertar el registro de auditoría
    $stmt = $con->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
    $stmt->bind_param('ssss', $accion, $descripcion, $id, $nombreUser);
    $stmt->execute();
    $stmt->close();
    $con->close();
}
?>
