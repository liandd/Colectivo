<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';

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
if ($stmt = $conexion->prepare('SELECT idUsuario, contrasenaUsuario FROM usuarios WHERE nombreUsuario = ?')) {
	$stmt->bind_param('s', $_POST['usuario']);
	$stmt->execute();
	// Guardar el dato para saber si existe en el servidor
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $contra = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);

        $stmt->bind_result($id, $contra);
        $stmt->fetch();
        // La cuenta existe, verificar contrasena:
        if (password_verify($_POST['contrasena'], $contra)) {
            // Inicio de sesion exitoso
            // Crea una sesion para ese usuario
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['usuario'];
            $_SESSION['id'] = $id;
            if($_SESSION['id']==1){
                header('Location: ../modelo/inicio.php');
            }
            else{
                header('Location: ../modelo/bienvenida.php');
            }
            
           // echo 'Bienvenido probando login de admin, se logea : ' . $_SESSION['nombre'] . '!';
        } else {
            // Incorrect password
            echo 'Datos erroneos!';
        }
    } else {
        // Incorrect username
        echo 'Datos erroneos!';
    }
	$stmt->close();
}
?>