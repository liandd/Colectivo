<?php
session_start();
require_once '../config/DatabaseConfig.php';
require_once '../model/audit.php';

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
            $_SESSION['name'] = $_POST['usuario'];
            $_SESSION['tipoUser'] = $userType;
            $_SESSION['id'] = $id;
            
            logAuthActivity('Login', $id, $_POST['usuario']);
            header('Location: ../model/home.php');
        } else {
            echo "<script>alert('Incorrect password!'); window.location='../index.php';</script>";
        }
    } else {
        echo "<script>alert('User does not exist!'); window.location='../index.php';</script>";
    }
    $stmt->close();
}
?>
