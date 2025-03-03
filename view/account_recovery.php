<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperación de Cuenta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login">
        <h1>Recuperación de Cuenta</h1>
        <form action="../controller/account_recovery.php" method="post">
            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="submit" value="Recuperar Cuenta">
        </form>
        <a href="../index.php">Volver al inicio</a>
    </div>
</body>
</html>
