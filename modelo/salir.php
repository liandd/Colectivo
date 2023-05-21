<?php
session_start();
$id = $_SESSION['id'];
session_destroy();
$nombreUsuario = "";

function registrarAuditoria($accion, $descripcion, $id) {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'Colectivo';
  
    // Crear la conexión a la base de datos
    $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if ($stmt = $con->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($nombreUsuario);
        $stmt->fetch();
        $stmt->close();
    }
    if ($con->connect_errno) {
      exit('No se pudo conectar al servidor: ' . $con->connect_error);
    }
      $test = ucfirst($nombreUsuario.$descripcion);
      // Insertar el registro de auditoría
      $stmt = $con->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES ( CURDATE(), CURTIME(), ?, ?, ?, ?)');
      $stmt->bind_param('ssss', $accion, $test, $id, $nombreUsuario);
      $stmt->execute();
      $stmt->close();
      $con->close();
}
registrarAuditoria('Cierre de sesión', ', salió de la pagina.', $id);
header('Location: ../index.php');
?>