<?php
require_once '../config/SessionManager.php';
require_once '../config/DatabaseConfig.php';
require_once 'audit.php';

SessionManager::checkLogin();
SessionManager::checkAdminRights();

$conn = getDBConnection();
$audit = AuditLogger::getInstance();

// Get form data
$userId = $_POST['userId'];
$username = $_POST['username'];
$email = $_POST['email'];
$userType = $_POST['userType'];

// Get original data for audit
$stmt = $conn->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($originalUsername);
$stmt->fetch();
$stmt->close();

// Update user data
$stmt = $conn->prepare('UPDATE usuarios SET nombreUsuario = ?, correoUsuario = ?, tipoUsuario = ? WHERE idUsuario = ?');
$stmt->bind_param('sssi', $username, $email, $userType, $userId);
$success = $stmt->execute();

if ($success) {
    $changes = "nuevo nombre: $username, email: $email, tipo: $userType";
    $audit->logUserUpdate(
        SessionManager::getUserId(), 
        SessionManager::getUsername(),
        $originalUsername,
        $changes
    );
    echo "<script>alert('Update successful!');window.location='settings.php';</script>";
} else {
    echo "<script>alert('Update failed!');window.location='settings.php';</script>";
}

$stmt->close();
$conn->close();
?>
