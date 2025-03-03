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
$sqlFirstUser = "SELECT * FROM usuarios WHERE idUsuario = 1";
$resultFirstUser = mysqli_query($conexion, $sqlFirstUser);

if (!$resultFirstUser) {
    exit('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
$firstUser = mysqli_fetch_assoc($resultFirstUser);
// Reposicionar el puntero del resultado para omitir el primer usuario
mysqli_data_seek($resultFirstUser, 0);
// Obtener todos los usuarios (excepto el primero) de la base de datos
$sqlUsers = "SELECT * FROM usuarios WHERE idUsuario != 1";
$resultado = mysqli_query($conexion, $sqlUsers);
if (!$resultado) {
    exit('Error al ejecutar la consulta: ' . mysqli_error($conexion));
}
$user = $_SESSION['name'];
$correo = $firstUser['correoUsuario'];
$contra = $firstUser['contrasenaUsuario'];
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
		<link rel="stylesheet" href="../css/ajustes.css" type="text/css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
        <meta charset="utf-8">
    <title>Pagina</title>
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
          <td><?=$firstUser['idUsuario']?></td>
          <td><?=$firstUser['nombreUsuario']?></td>
          <td>
            <span class="password" data-password="<?=$firstUser['contrasenaUsuario']?>">
              ********
            </span>
          </td>
          <td>
            <button class="toggle-password">Mostrar</button>
          </td>
          <td><?=$firstUser['correoUsuario']?></td>
          <td><?=$firstUser['tipoUsuario']?></td>
          <td>No es posible.</td>
          <td>No es posible.</td>
        </tr>
            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
                ?>
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
                    <td><a href="editar.php?i=<?php echo $fila['idUsuario']; ?>" style="color:#AC094C">Editar</a></td>
                    <td><a href="eliminar.php?i=<?php echo $fila['idUsuario']; ?>" style="color:#AC094C">Eliminar</a></td>
                </tr>
                <?php
            }
            ?>
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