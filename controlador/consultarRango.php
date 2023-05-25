<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Consulta de rangos de frecuencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="submit"] {
            margin-top: 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #5A062F;
            color: #ffffff;
        }
        input[type="submit"] { 
            border:none; 
            display: inline-block;
            padding: 10px 20px;
            background-color: #AC094C; 
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Consulta de rangos de frecuencias</h2>
    <form method="post" action="consultarRango.php">
       
        <?php if ($_SESSION['name'] === 'webMaster') { ?> 
        <label for="usuario">Usuario: <?=$_SESSION['name']?></label>
        <input type="text" id="usuario" name="usuario" value="<?=$_SESSION['name']?>">
        <?php } else { ?>
        <label for="usuario">Usuario: <?=$_SESSION['name']?></label>
        <input type="hidden" id="usuario" name="usuario" value="<?=$_SESSION['name']?>">
        <?php } ?>

        <br>
        <input type="submit" value="Consultar">
    </form>

    <?php
    // Verificar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener el ID del usuario seleccionado
        $nombreUsuario = $_POST["usuario"];

        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Colectivo";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        // Consultar los rangos de frecuencias para el usuario seleccionado
        //$sql = "SELECT rango_de_frecuencias FROM rango_de_frecuencias WHERE nombreUsuario = $nombreUsuario";
        $sql = "SELECT rango_de_frecuencias 
        FROM rango_de_frecuencias 
        INNER JOIN usuarios ON usuarios.idUsuario = rango_de_frecuencias.idUsuario 
        WHERE usuarios.nombreUsuario = '$nombreUsuario'";
        $result = $conn->query($sql);

        // Mostrar los resultados en una tabla
        if ($result->num_rows > 0) {
            echo "<h3>Rangos de frecuencias para el usuario seleccionado:</h3>";
            echo "<table>";
            echo "<tr><th>Rango de frecuencias</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["rango_de_frecuencias"] . "</td></tr>";
            }
            echo "</table>";
            ?>
            <iframe src="../vista/espectro.html" width="100%" height="100%" frameborder="0" style="position: absolute;" allowfullscreen></iframe>
            <?php
        } else {
            echo "No se encontraron rangos de frecuencias para el usuario seleccionado.";
        }

        // Cerrar la conexión a la base de datos
        $conn->close();

    }
    ?>
</body>
</html>
