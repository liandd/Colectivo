<?php
require_once '../config/SessionManager.php';
require_once '../config/DatabaseConfig.php';

SessionManager::checkLogin();

$userId = SessionManager::getUserId();
$username = SessionManager::getUsername();

$connection = getDBConnection();

// Get user data from server
$stmt = $connection->prepare('SELECT tipoUsuario, correoUsuario FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($userType, $userEmail);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Profile Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </head>
    <body class="loggedin">
        <nav class="navtop">
            <div>
                <h1>Title</h1>
                <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </nav>
        <div class="content">
            <h2>User Profile</h2>
            <div>
                <p>Your account information:</p>
                <table>
                    <tr>
                        <td>Username:</td>
                        <td><?=$username?></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><?=$userEmail?></td>
                    </tr>
                    <tr>
                        <td>User Type:</td>
                        <td><?=$userType?></td>
                    </tr>
                </table>
            </div>
            <form action="../controller/create_account.php" method="post">
                <h2>Create Account</h2>
                <div class="input-box">
                    <span class="icon"><i class='bx bxs-user'></i></span>
                    <input type="text" required name="username">
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class='bx bxs-envelope'></i></span>
                    <input type="email" required name="email">
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="password" required name="password">
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class='bx bxs-lock-alt'></i></span>
                    <input type="text" required name="userType">
                    <label>User Type</label>
                </div>
                <button class="btn" name="Create_Account">Create Account</button>
            </form>
        </div>
    </body>
</html>
