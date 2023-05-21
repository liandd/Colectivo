<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'Colectivo';
// Obtener el ID del usuario a editar desde el parámetro de la URL
if (isset($_GET['i'])) {
    $idUsuario = $_GET['i'];
} else {
    ?> <script>alert('Datos erroneos, No se encuentra el usuario!');window.location='../index.php' </script> <?php
    exit();
}
// Conectar a la base de datos
$conn = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($conn->connect_errno) {
    exit('No se pudo conectar al servidor: ' . $conn->connect_error);
}
// Obtener los datos del usuario a partir de su ID
$stmt = $conn->prepare('SELECT * FROM usuarios WHERE idUsuario = ?');
$stmt->bind_param('i', $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    // El usuario existe, obtener los datos
    $usuario = $resultado->fetch_assoc();
    ?>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="idUsuario" value="<?php echo $usuario['idUsuario']; ?>">
        <label for="nombreUsuario">Nombre de Usuario:</label>
        <input type="text" name="nombreUsuario" value="<?php echo $usuario['nombreUsuario']; ?>"><br>
        <label for="correoUsuario">Correo:</label>
        <input type="email" name="correoUsuario" value="<?php echo $usuario['correoUsuario']; ?>"><br>
        <label for="tipoUsuario">Tipo de Usuario:</label>
        <input type="text" name="tipoUsuario" value="<?php echo $usuario['tipoUsuario']; ?>"><br>
        <button type="submit">Actualizar</button>
    </form>
    <?php
} else {
    ?> <script>alert('Datos erroneos, No se encuentra el usuario!');window.location='../index.php' </script> <?php
    exit();
}

$stmt->close();
$conn->close();
?>
