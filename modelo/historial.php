<?php
session_start();
// Si el usuario no está logeado
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../index.php');
    exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';
$conexion = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('No se pudo conectar al servidor: ' . mysqli_connect_error());
}

// Obtener los últimos 10 logs de auditoría
$sqlLogs = "SELECT * FROM logs_auditoria ORDER BY fechaLogs_auditoria DESC, horaLogs_auditoria DESC LIMIT 10";
$resultadoLogs = mysqli_query($conexion, $sqlLogs);
if (!$resultadoLogs) {
    exit('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Página de Ajustes</title>
    <link rel="stylesheet" href="../css/ajustes.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            setInterval(function () {
                $('#tabla-usuarios').load(' #tabla-usuarios', function () {
                    // Obtener la cantidad de filas en la tabla antes de la actualización
                    let filasAntes = $('#tabla-usuarios tbody tr').length;

                    // Realizar la actualización de la tabla
                    $('#tabla-usuarios').load('actualizarTabla.php', function () {
                        // Obtener la cantidad de filas en la tabla después de la actualización
                        let filasDespues = $('#tabla-usuarios tbody tr').length;
                        // Comparar la cantidad de filas antes y después de la actualización
                        if (filasDespues > filasAntes) {
                            // Si se agregó un nuevo registro, reproducir un sonido
                            let sound = document.getElementById("sound");
                            sound.play();
                        }
                    });
                });
            }, 2100); // 2 segundos
        });
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Ajustes</h1>
        <a href="perfil.php"><i class="fas fa-user-circle"></i>Perfil</a>
        <a href="salir.php"><i class="fas fa-sign-out-alt"></i>Salir</a>
    </div>
</nav>
<div class="content">
    <h2>Historial de Auditoría</h2>
    <div>
        <?php if (mysqli_num_rows($resultadoLogs) > 0) : ?>
            <table id="tabla-usuarios" class="styled-table">
                <tr>
                    <th>Id Logs</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>Nombre de Usuario</th>
                    <th>Id Usuario</th>
                </tr>
                <?php while ($fila = mysqli_fetch_assoc($resultadoLogs)) : ?>
                    <tr>
                        <td><?php echo $fila['idLogs_auditoria']; ?></td>
                        <td><?php echo $fila['fechaLogs_auditoria']; ?></td>
                        <td><?php echo $fila['horaLogs_auditoria']; ?></td>
                        <td><?php echo $fila['accionLogs_auditoria']; ?></td>
                        <td><?php echo $fila['descripcionLogs_auditoria']; ?></td>
                        <td><?php echo $fila['nombreUsuario']; ?></td>
                        <td><?php echo $fila['idUsuario']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No hay registros de auditoría.</p>
        <?php endif; ?>
    </div>
    <audio id="sound" src="../files/bubble.mp3"></audio>
</div>
</body>
</html>
