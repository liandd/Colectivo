<?php
session_start();
// Si el usuario no esta logeado
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
// Test Tabla de usuarios para el admin tener control
$sql = "SELECT * FROM usuarios";
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    exit('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
// Empezar desde el segundo usuario en la db
mysqli_data_seek($resultado, 1);
//Traer los datos del usuario del servidor
$stmt = $conexion->prepare('SELECT tipoUsuario, correoUsuario, contrasenaUsuario FROM usuarios WHERE idUsuario = ?');
// Traer datos con el id del usuario
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($tipoUsuario, $correoUsuario, $contrasenaUsuario);
$stmt->fetch();
$stmt->close();
$user = $tipoUsuario;
$correo = $correoUsuario;
$contra = $contrasenaUsuario;

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Pagina</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <meta charset="utf-8">
    <title>Pagina</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        table, th, td {
         border:1px solid black;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
        }

        .styled-table thead th {
            background-color: #f2f2f2;
            color: #000;
            font-weight: bold;
            padding: 10px;
            text-align: left;
        }

        .styled-table tbody td {
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .styled-table tbody tr:last-child td {
            border-bottom: none;
        }
    </style>
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
    <h2>Perfiles de usuarios</h2>
    <div>
        <p>Informacion de las cuentas desde el panel Administrativo:</p>
        <table id="tabla-usuarios" class="styled-table">
            <tr>
                <th>Id de Usuario</th>
                <th>Nombre de Usuario</th>
                <th>Contrasena</th>
                <th>Mostrar</th>
                <th>Correo</th>
                <th>Tipo de Usuario</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
            <tr>
                <td><?=$_SESSION['id']?></td>
                <td><?=$_SESSION['name']?></td>
                <td>
                <span class="password" data-password="<?=$contra?>">
                    ********
                </span>
                </td>
                <td>
                    <button class="toggle-password">Mostrar</button>
                </td>
                <td><?=$correo?></td>
                <td><?=$user?></td>
                <td><a href="editar.php?i=<?php echo $i; ?>">Editar</a></td>
                <td>No es posible.</td>
            </tr>
            <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                <tr>
            <td><?php echo $fila['idUsuario']; ?></td>
            <td><?php echo $fila['nombreUsuario']; ?></td>
            <td>
                <span class="password" data-password="<?php echo $fila['contrasenaUsuario']; ?>">
                    ********
                </span>
            </td>
            <td>
                <button class="toggle-password">Mostrar</button>
            </td>
            <td><?php echo $fila['correoUsuario']; ?></td>
            <td><?php echo $fila['tipoUsuario']; ?></td>
            <td><a href="editar.php?i=<?php echo $i; ?>">Editar</a></td>
            <td><a href="eliminar.php?i=<?php echo $i; ?>">Eliminar</a></td>
            </tr>
            <?php } ?>
        </table>
    <script>
        var toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.parentNode.parentNode;
            var passwordElement = row.querySelector('.password');
            var password = passwordElement.getAttribute('data-password');
            if (passwordElement.textContent === '********') {
                passwordElement.textContent = password;
                this.textContent = 'Ocultar';
            } else {
                passwordElement.textContent = '********';
                this.textContent = 'Mostrar';
            }
        });
    });
</script>
    </div>
</div>
</body>
</html>