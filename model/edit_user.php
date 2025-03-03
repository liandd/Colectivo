<?php
require_once '../config/SessionManager.php';
require_once '../config/DatabaseConfig.php';

SessionManager::checkLogin();
SessionManager::checkAdminRights();

// Get user ID from URL parameter
if (!isset($_GET['i'])) {
    echo "<script>alert('Invalid data, user not found!');window.location='../index.php'</script>";
    exit();
}

$userId = $_GET['i'];
$conn = getDBConnection();

// Prepare and execute query
$stmt = $conn->prepare('SELECT * FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    ?>
    <form action="update_user.php" method="POST">
        <input type="hidden" name="userId" value="<?php echo $user['idUsuario']; ?>">
        
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo $user['nombreUsuario']; ?>"><br>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $user['correoUsuario']; ?>"><br>
        
        <label for="userType">User Type:</label>
        <input type="text" name="userType" id="userType" value="<?php echo $user['tipoUsuario']; ?>"><br>
        
        <button type="submit">Update</button>
    </form>
    <?php
} else {
    echo "<script>alert('Invalid data, user not found!');window.location='../index.php'</script>";
    exit();
}

// Close connections
$stmt->close();
$conn->close();
?>
