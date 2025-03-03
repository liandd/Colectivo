<?php
session_start();
require_once '../config/DatabaseConfig.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Frequency Range Query</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; }
		h2 { margin-bottom: 10px; }
		label { display: block; margin-bottom: 5px; }
		input[type="submit"] { margin-top: 10px; border:none; padding: 10px 20px; background-color: #AC094C; color: #fff; cursor: pointer; transition: background-color 0.3s; }
		table { border-collapse: collapse; width: 100%; }
		th, td { border: 1px solid #ddd; padding: 8px; }
		th { background-color: #5A062F; color: #fff; }
	</style>
</head>
<body>
	<h2>Frequency Range Query</h2>
	<form method="post" action="view_frequency_range.php">
		<?php if ($_SESSION['name'] === 'webMaster') { ?>
			<label for="usuario">User: <?=$_SESSION['name']?></label>
			<input type="text" id="usuario" name="usuario" value="<?=$_SESSION['name']?>">
		<?php } else { ?>
			<label for="usuario">User: <?=$_SESSION['name']?></label>
			<input type="hidden" id="usuario" name="usuario" value="<?=$_SESSION['name']?>">
		<?php } ?>
		<br>
		<input type="submit" value="Query">
	</form>
	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$userName = $_POST["usuario"];
		
		$sql = "SELECT rdf.rango_de_frecuencias 
		        FROM rango_de_frecuencias rdf 
		        INNER JOIN usuarios u ON u.idUsuario = rdf.idUsuario 
		        WHERE u.nombreUsuario = ?";
		$stmt = $conexion->prepare($sql);
		$stmt->bind_param('s', $userName);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows > 0) {
			echo "<h3>Frequency ranges for selected user:</h3>";
			echo "<table><tr><th>Frequency Range</th></tr>";
			while ($row = $result->fetch_assoc()) {
				echo "<tr><td>" . $row["rango_de_frecuencias"] . "</td></tr>";
			}
			echo "</table>";
			?>
			<iframe src="../view/espectro.html" width="100%" height="100%" frameborder="0" style="position: absolute;" allowfullscreen></iframe>
			<?php
		} else {
			echo "No frequency ranges found for the selected user.";
		}
		$stmt->close();
		$conexion->close();
	}
	?>
</body>
</html>
