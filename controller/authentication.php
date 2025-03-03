<?php
session_start();
require_once '../config/database.php';

function registerAudit($action, $description, $userId) {
	require_once '../config/database.php';
	// ...existing code to retrieve username...
	if ($stmt = $conexion->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
		$stmt->bind_param('i', $_SESSION['id']);
		$stmt->execute();
		$stmt->bind_result($username);
		$stmt->fetch();
		$stmt->close();
	}
	$combinedDesc = ucfirst($username . $description);
	$stmt = $conexion->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
	$stmt->bind_param('ssss', $action, $combinedDesc, $userId, $username);
	$stmt->execute();
	$stmt->close();
	$conexion->close();
}

if (!isset($_POST['usuario'], $_POST['contrasena'])) {
	exit('Please enter your login details again!');
}

if ($stmt = $conexion->prepare('SELECT idUsuario, tipoUsuario, contrasenaUsuario FROM usuarios WHERE nombreUsuario = ?')) {
	$stmt->bind_param('s', $_POST['usuario']);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		$stmt->bind_result($id, $userType, $hashedPassword);
		$stmt->fetch();
		if (password_verify($_POST['contrasena'], $hashedPassword)) {
			session_regenerate_id(true);
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name']     = $_POST['usuario'];
			$_SESSION['tipoUser'] = $userType;
			$_SESSION['id']       = $id;
			registerAudit('Login', ', logged in.', $id);
			if ($_SESSION['tipoUser'] == "Admin") {
				header('Location: ../model/home.php');
			} else {
				header('Location: ../model/welcome.php');
			}
		} else {
			echo "<script>alert('Incorrect password!'); window.location='../index.php';</script>";
		}
	} else {
		echo "<script>alert('User does not exist!'); window.location='../index.php';</script>";
	}
	$stmt->close();
}
?>
