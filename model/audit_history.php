<?php
require_once '../config/DatabaseConfig.php';
SessionManager::checkLogin();

$conn = getDBConnection();

$sqlLogs = "SELECT * FROM logs_auditoria ORDER BY fechaLogs_auditoria DESC, horaLogs_auditoria DESC LIMIT 10";
$resultLogs = mysqli_query($conn, $sqlLogs);
if (!$resultLogs) {
    exit('Error executing query: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Settings Page</title>
    <link rel="stylesheet" href="../css/settings.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            setInterval(function () {
                $('#user-table').load(' #user-table', function () {
                    let rowsBefore = $('#user-table tbody tr').length;

                    $('#user-table').load('updateTable.php', function () {
                        let rowsAfter = $('#user-table tbody tr').length;
                        if (rowsAfter > rowsBefore) {
                            let sound = document.getElementById("sound");
                            sound.play();
                        }
                    });
                });
            }, 2100);
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
        <?php if (mysqli_num_rows($resultLogs) > 0) : ?>
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
                <?php while ($row = mysqli_fetch_assoc($resultLogs)) : ?>
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
