<?php
require_once 'AuditManager.php';
SessionManager::checkLogin();

$auditManager = AuditManager::getInstance();
$resultLogs = $auditManager->getAuditHistory(10);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit History</title>
    <link rel="stylesheet" href="../css/settings.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            setInterval(function() {
                $('#user-table').load('updateTable.php', function(response, status, xhr) {
                    if (status === 'success') {
                        let sound = document.getElementById("sound");
                        sound.play();
                    }
                });
            }, 2100);
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Settings</h1>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<div class="content">
    <h2>Audit History</h2>
    <div>
        <?php if ($resultLogs->num_rows > 0) : ?>
            <table id="user-table" class="styled-table">
                <tr>
                    <th>Log ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Username</th>
                    <th>User ID</th>
                </tr>
                <?php while ($row = $resultLogs->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['idLogs_auditoria']; ?></td>
                        <td><?php echo $row['fechaLogs_auditoria']; ?></td>
                        <td><?php echo $row['horaLogs_auditoria']; ?></td>
                        <td><?php echo $row['accionLogs_auditoria']; ?></td>
                        <td><?php echo $row['descripcionLogs_auditoria']; ?></td>
                        <td><?php echo $row['nombreUsuario']; ?></td>
                        <td><?php echo $row['idUsuario']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No audit records found.</p>
        <?php endif; ?>
    </div>
    <audio id="sound" src="../files/bubble.mp3"></audio>
</div>
</body>
</html>
