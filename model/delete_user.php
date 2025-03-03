<?php
session_start();
require_once '../config/DatabaseConfig.php';
require_once 'audit.php';

// Verify if user ID to delete is provided
if (isset($_GET['i'])) {
    $userIdToDelete = $_GET['i'];
} else {
    header('Location: ../index.php');
    exit();
}

// Create database connection
$conn = getDBConnection();
if ($conn->connect_errno) {
    exit('Could not connect to server: ' . $conn->connect_error);
}

// Get user data to delete
$stmt = $conn->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $userIdToDelete);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $usernameToDelete = $user['nombreUsuario'];
} else {
    header('Location: ../index.php');
    exit();
}

// Get admin username who is performing the deletion
$adminUsername = $_SESSION['name'];

// Build description for audit log
$description = ucfirst($adminUsername . " deleted user " . $usernameToDelete);

// Record audit log
logActivity('User Deletion', $description, $_SESSION['id'], $adminUsername);

// Delete user from database
$stmt = $conn->prepare('DELETE FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $userIdToDelete);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('User deleted successfully!');window.location='settings.php';</script>";
} else {
    echo "<script>alert('Could not delete the user!');window.location='settings.php';</script>";
}

$stmt->close();
$conn->close();
?>
