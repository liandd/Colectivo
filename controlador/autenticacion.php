<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';
$nombreUsuario = "";

function registrarAuditoria($accion, $descripcion, $id) {
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'Colectivo';
  // Crear la conexión a la base de datos
  $con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
  
  if ($stmt = $con->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($nombreUsuario);
    $stmt->fetch();
    $stmt->close();
  }
  if ($con->connect_errno) {
    exit('No se pudo conectar al servidor: ' . $con->connect_error);
  }
    $test = ucfirst($nombreUsuario.$descripcion);
    // Insertar el registro de auditoría
    $stmt = $con->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES ( CURDATE(), CURTIME(), ?, ?, ?, ?)');
    $stmt->bind_param('ssss', $accion, $test, $id, $nombreUsuario);
    $stmt->execute();
    $stmt->close();
    $con->close();
}

$conexion = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ( mysqli_connect_errno() ) {
	//Si hay un error mostrar:
	exit('No se pudo conectar al servidor: ' . mysqli_connect_error());
}
//Mirar si los datos del formulario son correctos:
if ( !isset($_POST['usuario'], $_POST['contrasena']) ) {
	//Si no puede validar
	exit('Ingrese nuevamente los datos para ingresar!');
}

//Consulta sql evitando sql injection:
if ($stmt = $conexion->prepare('SELECT idUsuario, tipoUsuario, contrasenaUsuario FROM usuarios WHERE nombreUsuario = ?')) {
	$stmt->bind_param('s', $_POST['usuario']);
	$stmt->execute();
	// Guardar el dato para saber si existe en el servidor
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $contra = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

        $stmt->bind_result($id, $tipoUser, $contra);
        $stmt->fetch();
        // La cuenta existe, verificar contrasena:
        if (password_verify($_POST['contrasena'], $contra)) {
            // Inicio de sesion exitoso
            // Crea una sesion para ese usuario
            session_regenerate_id(true);
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['usuario'];
            $_SESSION['tipoUser'] = $tipoUser;
            $_SESSION['id'] = $id;
            registrarAuditoria('Inició sesión', ', entró a la pagina.', $id);
            if($_SESSION['tipoUser']=="Admin"){
                header('Location: ../modelo/inicio.php');
            }
            else{
                header('Location: ../modelo/bienvenida.php');
            }
            
           // echo 'Bienvenido probando login de admin, se logea : ' . $_SESSION['nombre'] . '!';
        } else {
            // Incorrect password
            ?> <script>alert('Datos erroneos, Contraseña incorrecta!');window.location='../index.php' </script> <?php
        }
    } else {
        // Incorrect username
        ?> <script>alert('Datos erroneos, Usuario no existe!');window.location='../index.php' </script> <?php
    }
	$stmt->close();
}
?>