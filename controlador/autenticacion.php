<?php
session_start();
require_once '../config/database.php';
$nombreUsuario = "";

function registrarAuditoria($accion, $descripcion, $id) {
  require_once '../config/database.php';
  if ($stmt = $conexion->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($nombreUsuario);
    $stmt->fetch();
    $stmt->close();
  }
  if ($conexion->connect_errno) {
    exit('No se pudo conectar al servidor: ' . $conexion->connect_error);
  }
  $test = ucfirst($nombreUsuario.$descripcion);
  $stmt = $conexion->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES ( CURDATE(), CURTIME(), ?, ?, ?, ?)');
  $stmt->bind_param('ssss', $accion, $test, $id, $nombreUsuario);
  $stmt->execute();
  $stmt->close();
  $conexion->close();
}

if (!isset($_POST['usuario'], $_POST['contrasena'])) {
	exit('Ingrese nuevamente los datos para ingresar!');
}

if ($stmt = $conexion->prepare('SELECT idUsuario, tipoUsuario, contrasenaUsuario FROM usuarios WHERE nombreUsuario = ?')) {
	$stmt->bind_param('s', $_POST['usuario']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $tipoUser, $contraHash);
        $stmt->fetch();
        if (password_verify($_POST['contrasena'], $contraHash)) {
            session_regenerate_id(true);
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['usuario'];
            $_SESSION['tipoUser'] = $tipoUser;
            $_SESSION['id'] = $id;
            registrarAuditoria('Inició sesión', ', entró a la página.', $id);
            header('Location: ' . (($_SESSION['tipoUser']=="Admin") ? '../modelo/inicio.php' : '../modelo/bienvenida.php'));
        } else {
            echo "<script>alert('Datos erroneos, Contraseña incorrecta!'); window.location='../index.php';</script>";
        }
    } else {
        echo "<script>alert('Datos erroneos, Usuario no existe!'); window.location='../index.php';</script>";
    }
	$stmt->close();
}
?>