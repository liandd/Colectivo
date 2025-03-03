<!DOCTYPE html>
<html>
<head>
  <title>Calculadora de Modulación FM</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }
    
    h1 {
      color: #333;
    }
    
    form {
      margin-top: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 10px;
    }
    
    input[type="text"] {
      width: 200px;
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
    
    #result {
      margin-top: 20px;
      font-weight: bold;
    }
  </style>
  <script>
    function calculateModulation() {
      let deltaf = parseFloat(document.getElementById('carrier-frequency').value);
      let fsubm  = parseFloat(document.getElementById('modulating-frequency').value);

      if(deltaf > 0 && fsubm > 0 ){
        let m = (deltaf / fsubm);
        let mporcent = (deltaf / fsubm)*100;
        document.getElementById('modulation').textContent = mporcent.toFixed(2) + "%";
        document.getElementById('modulation-index').textContent = m.toFixed(2);
         // Realizar una solicitud AJAX para enviar los datos a PHP
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'modulacion.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('deltaf='+encodeURIComponent(deltaf)+'&fsubm='+encodeURIComponent(fsubm)+'&m='+encodeURIComponent(m)+'&mporcent='+encodeURIComponent(mporcent));
      }
      else alert("Ingrese un valor valido.")
    }
  </script>

<?php
session_start();
require_once '../config/DatabaseConfig.php';

if (!isset($_SESSION['loggedin'])) {
	header('Location: ./index.php');
	exit;
}

if(isset($_POST['deltaf']) && isset($_POST['fsubm'])){  
    $deltaf = $_POST['deltaf'];
    $fsubm = $_POST['fsubm'];
    if($deltaf>0 && $fsubm>0){
    $m = $_POST['m'];
    $mporcent = $_POST['mporcent'];
  
      $descripcion="ha realizado una consulta de modulacion con los siguientes datos de entrada:\n". 
       "deltaF= $deltaf".", F_m= .$fsubm."."Los cuales han dado los siguientes resultados:\n".
       "m= $m"."M= $mporcent";
         // Llamar a la función registrarAuditoria con los datos recibidos
        registrarAuditoria($descripcion);
        //registrarAuditoria(); 
      }
    }
 function registrarAuditoria($descripcion) {
  $conn = getDBConnection();

  if ($stmt = $conn->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($nombreUsuario);
    $stmt->fetch();
    $stmt->close();
  }

  $descAbre = "El usuario ".$nombreUsuario.", ".$descripcion;

  // Insertar el registro de auditoría
  $stmt = $conn->prepare("INSERT INTO logs (message) values (?)");
  $stmt->bind_param('s', $descAbre);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}

  
?>

</head>
<body>
  <h1>Calculadora de Modulación FM</h1>
  
  <form id="fm-form" onsubmit="event.preventDefault();" method="post" action="modulacion.php">
    <label for="carrier-frequency">Frecuencia del portador (en Hz):</label>
    <input type="text" id="carrier-frequency"  name="deltaf" required>
    
    <label for="modulating-frequency">Frecuencia de modulación (en Hz):</label>
    <input type="text" id="modulating-frequency" name="fsubm"required>
    
    <input type="submit" value="Calcular" onclick="calculateModulation()">
  </form>
  
  <div id="result">
    <p>Índice de Modulación: <span id="modulation-index"></span></p>
    <p>Porcentaje de modulación: <span id="modulation"></span></p>
  </div>
</body>
</html>
