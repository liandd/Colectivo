<?php
require_once '../config/SessionManager.php';
require_once '../config/DatabaseConfig.php';

SessionManager::checkLogin();
SessionManager::checkAdminRights();

$conn = getDBConnection();

// Get the admin user (ID = 1)
$sqlFirstUser = "SELECT * FROM usuarios WHERE idUsuario = 1";
$resultFirstUser = mysqli_query($conn, $sqlFirstUser);
if (!$resultFirstUser) {
    exit('Query error: ' . mysqli_error($conn));
}
$firstUser = mysqli_fetch_assoc($resultFirstUser);

// Get all non-admin users
$sqlUsers = "SELECT * FROM usuarios WHERE idUsuario != 1";
$resultUsers = mysqli_query($conn, $sqlUsers);
if (!$resultUsers) {
    exit('Query error: ' . mysqli_error($conn));
}

// Get current user data
$stmt = $conn->prepare('SELECT tipoUsuario, correoUsuario, contrasenaUsuario FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($userType, $userEmail, $userPassword);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Profiles</title>
    <link rel="stylesheet" href="../css/settings.css" type="text/css">
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
        <h2>User Profiles</h2>
        <div>
            <p>Accounts information from the Admin panel:</p>
            <table id="user-table" class="styled-table">
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Show</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <tr>
                    <td><?=$firstUser['idUsuario']?></td>
                    <td><?=$firstUser['nombreUsuario']?></td>
                    <td>
                        <span class="password" data-password="<?=$firstUser['contrasenaUsuario']?>">********</span>
                    </td>
                    <td>
                        <button class="toggle-password">Show</button>
                    </td>
                    <td><?=$firstUser['correoUsuario']?></td>
                    <td><?=$firstUser['tipoUsuario']?></td>
                    <td>Not allowed</td>
                    <td>Not allowed</td>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($resultUsers)): ?>
                <tr>
                    <td><?=$row['idUsuario']?></td>
                    <td><?=$row['nombreUsuario']?></td>
                    <td>
                        <span class="password" data-password="<?=$row['contrasenaUsuario']?>">********</span>
                    </td>
                    <td>
                        <button class="toggle-password">Show</button>
                    </td>
                    <td><?=$row['correoUsuario']?></td>
                    <td><?=$row['tipoUsuario']?></td>
                    <td><a href="edit_user.php?i=<?=$row['idUsuario']?>" style="color:#AC094C">Edit</a></td>
                    <td><a href="delete_user.php?i=<?=$row['idUsuario']?>" style="color:#AC094C">Delete</a></td>
                </tr>
                <?php endwhile; ?>
            </table>
            <script>
                document.querySelectorAll('.toggle-password').forEach(button => {
                    button.addEventListener('click', function() {
                        const row = this.parentNode.parentNode;
                        const passElem = row.querySelector('.password');
                        const password = passElem.getAttribute('data-password');
                        
                        if (passElem.textContent.trim() === '********') {
                            passElem.textContent = password;
                            this.textContent = 'Hide';
                        } else {
                            passElem.textContent = '********';
                            this.textContent = 'Show';
                        }
                    });
                });
            </script>
        </div>
    </div>
</body>
</html>
