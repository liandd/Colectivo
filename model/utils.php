<?php
require_once '../config/DatabaseConfig.php';

function getUserData($userId) {
    $conn = getDBConnection();
    $stmt = $conn->prepare('SELECT * FROM usuarios WHERE idUsuario = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $data;
}
?>
