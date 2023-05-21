<?php
session_start();
$id = $_SESSION['id'];
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';
// Obtener los datos enviados por el formulario
$idUsuario = $_POST['idUsuario'];
$nombreUsuario = $_POST['nombreUsuario'];
$correoUsuario = $_POST['correoUsuario'];
$tipoUsuario = $_POST['tipoUsuario'];

// Crear la conexión a la base de datos
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($con->connect_errno) {
    exit('No se pudo conectar al servidor: ' . $con->connect_error);
}

// Obtener el nombre de usuario antes de la actualización
$nombreUser = "";
if ($stmt = $con->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();
    $stmt->bind_result($nombreUser);
    $stmt->fetch();
    $stmt->close();
}

// Obtener el nombre de usuario del administrador que realiza los cambios
$adminNombreUsuario = $_SESSION['name'];

// Construir la descripción para el registro de auditoría
$descripcion = "actualizó los datos de";
$descripcionCompleta = ucfirst($adminNombreUsuario . " " . $descripcion . " " . $nombreUser . " - Nuevo nombre de usuario: " . $nombreUsuario . ", Nuevo correo: " . $correoUsuario . ", Nuevo tipo de usuario: " . $tipoUsuario);

// Insertar el registro de auditoría
registrarAuditoria('Actualización de datos', $descripcionCompleta, $id, $adminNombreUsuario);

// Actualizar los datos del usuario en la base de datos
$stmt = $con->prepare('UPDATE usuarios SET nombreUsuario = ?, correoUsuario = ?, tipoUsuario = ? WHERE idUsuario = ?');
$stmt->bind_param('sssi', $nombreUsuario, $correoUsuario, $tipoUsuario, $idUsuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Los datos se actualizaron correctamente
    echo "<script>alert('Actualización exitosa!');window.location='ajustes.php';</script>";
    exit();
} else {
    // No se pudo actualizar los datos
    echo "<script>alert('No fue posible actualizar!');window.location='ajustes.php';</script>";
    exit();
}

$stmt->close();
$con->close();

function registrarAuditoria($accion, $descripcion, $id, $nombreUser) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'Colectivo';

    // Crear la conexión a la base de datos
    $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ($con->connect_errno) {
        exit('No se pudo conectar al servidor: ' . $con->connect_error);
    }
    
    // Insertar el registro de auditoría
    $stmt = $con->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
    $stmt->bind_param('ssss', $accion, $descripcion, $id, $nombreUser);
    $stmt->execute();
    $stmt->close();
    $con->close();
}
?>
