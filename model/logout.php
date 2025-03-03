<?php
session_start();
require_once 'audit.php';

// Capturar ID antes de destruir sesión
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$username = isset($_SESSION['name']) ? $_SESSION['name'] : null;

if ($userId && $username) {
    logAuthActivity('Logout', $userId, $username);
}

// Limpiar y destruir sesión
$_SESSION = array();
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}
session_destroy();

header('Location: ../index.php');
exit();
?>
