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

if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['Crear_Cuenta'])){
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = trim($_POST['contrasena']);
    $contraHashed = password_hash($contrasena, PASSWORD_DEFAULT);

    if ($query = $conexion->prepare("SELECT * FROM usuarios WHERE correoUsuario = ?")){
        $query->bind_param('s',$correo);
        $query->execute();
        $query->store_result();
        if ($query->num_rows > 0){
            ?> <script>alert('El correo ya ha sido registrado!');window.location='../index.php' </script> <?php
            
        }
        else if (strlen($contrasena) < 6){
            ?> <script>alert('La contrasena debe tener mas de 7 Caracteres!';);window.location='../index.php' </script> <?php
        }
        else {
            $stmt = $conexion->prepare("INSERT INTO usuarios(nombreUsuario,correoUsuario,contrasenaUsuario) VALUES ('$nombre', '$correo', '$contraHashed');");
            $exec = $stmt->execute();
            if ($exec){
                ?> <script>alert('Te has registrado!';);window.location='../index.php' </script> <?php
            }
            else {
                ?> <script>alert('Algo ha salido mal!';);window.location='../index.php' </script> <?php
            }
        }
    }
    $query->close();
    mysqli_close($conexion);
}
?>