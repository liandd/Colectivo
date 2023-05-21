<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';

// Verificar si se ha proporcionado el ID del usuario a eliminar
if (isset($_GET['i'])) {
    $idUsuario = $_GET['i'];
} else {
    // Redirigir si no se proporciona el ID del usuario
    header('Location: ../index.php');
    exit();
}

// Crear la conexión a la base de datos
$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($conn->connect_errno) {
    exit('No se pudo conectar al servidor: ' . $conn->connect_error);
}

// Obtener los datos del usuario a eliminar
$stmt = $conn->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    // El usuario existe, obtener el nombre de usuario
    $usuario = $resultado->fetch_assoc();
    $nombreUsuario = $usuario['nombreUsuario'];
} else {
    // Redirigir si no se encuentra el usuario
    header('Location: ../index.php');
    exit();
}

// Obtener el nombre de usuario del administrador que realiza la eliminación
$adminNombreUsuario = $_SESSION['name'];

// Construir la descripción para el registro de auditoría
$descripcion = "eliminó al usuario";
$descripcionCompleta = ucfirst($adminNombreUsuario . " " . $descripcion . " " . $nombreUsuario);

// Insertar el registro de auditoría
registrarAuditoria('Eliminación de usuario', $descripcionCompleta, $adminNombreUsuario);

// Eliminar el usuario de la base de datos
$stmt = $conn->prepare('DELETE FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $idUsuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // La eliminación fue exitosa
    echo "<script>alert('Usuario eliminado correctamente!');window.location='ajustes.php';</script>";
    exit();
} else {
    // No se pudo eliminar el usuario
    echo "<script>alert('No fue posible eliminar el usuario!');window.location='ajustes.php';</script>";
    exit();
}

$stmt->close();
$conn->close();

function registrarAuditoria($accion, $descripcion, $nombreUser) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'Colectivo';

    // Crear la conexión a la base de datos
    $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ($con->connect_errno) {
        exit('No se pudo conectar al servidor: ' . $con->connect_error);
    }

    // Obtener el ID del administrador que realiza la eliminación
    $stmt = $con->prepare('SELECT idUsuario FROM usuarios WHERE nombreUsuario = ?');
    $stmt->bind_param('s', $nombreUser);
    $stmt->execute();
    $stmt->bind_result($id);
    $stmt->fetch();
    $stmt->close();
    
    // Insertar el registro de auditoría
    $stmt = $con->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
    $stmt->bind_param('ssis', $accion, $descripcion, $id, $nombreUser);
    $stmt->execute();
    $stmt->close();
    $con->close();
}
?>
