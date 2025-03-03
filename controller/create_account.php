<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Create_Account'])) {
	$name         = trim($_POST['nombre']);
	$email        = trim($_POST['correo']);
	$password     = trim($_POST['contrasena']);
	$userType     = trim($_POST['tipoUsuario']);
	$passwordHashed = password_hash($password, PASSWORD_DEFAULT);
	$userType     = ($userType !== 'user') ? 'Admin' : 'user';

	$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correoUsuario = ?");
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		echo "<script>alert('Email already registered!'); window.location='../index.php';</script>";
	} else if (strlen($password) < 7) {
		echo "<script>alert('Password must have at least 7 characters!'); window.location='../index.php';</script>";
	} else {
		$stmt = $conexion->prepare("INSERT INTO usuarios(nombreUsuario, tipoUsuario, correoUsuario, contrasenaUsuario) VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssss', $name, $userType, $email, $passwordHashed);
		$exec = $stmt->execute();
		if ($exec) {
			$id = $conexion->insert_id;
			$frequencyRanges = array(
				'Ondas de radio: de 3 kHz a 300 GHz',
				'Microondas: de 300 MHz a 300 GHz',
				'Infrarrojo: de 300 GHz a 400 THz',
				'Luz visible: de 400 THz a 800 THz',
				'Ultravioleta: de 800 THz a 30 PHz',
				'Rayos X: de 30 PHz a 30 EHz',
				'Rayos gamma: más de 30 EHz'
			);
			foreach ($frequencyRanges as $range) {
				$stmtRange = $conexion->prepare("INSERT INTO rango_de_frecuencias (rango_de_frecuencias, idUsuario) VALUES (?, ?)");
				$stmtRange->bind_param('si', $range, $id);
				$stmtRange->execute();
			}
			echo "<script>alert('Account created!'); window.location='../index.php';</script>";
		} else {
			echo "<script>alert('Something went wrong!'); window.location='../index.php';</script>";
		}
	}
	$stmt->close();
	$conexion->close();
}
?>
