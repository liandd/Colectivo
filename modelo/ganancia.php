<!DOCTYPE html>
<html>
<head>
    <title>Calculadora de ganancia del Amplificador</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
    
        h1 {
            color: #333;
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
        form {
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
        }
        p {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Calculadora de ganancia del Amplificador</h1>
    </header>
    <form method="POST" action="">
        <label for="pi">Potencia de entrada(Watts):</label>
        <input type="float" id="pi" name="pi" required>
        <br>
        <label for="po">Potencia de salida(Watts):</label>
        <input type="float" id="po" name="po" required>
        <input type="submit" name="calcular" value="Calcular">
    </form>
    <div></div>

<?php
function clcGain($potenciaSalida, $potenciaEntrada) {
    $ganancia_dB = 10 * log10($potenciaSalida / $potenciaEntrada);
    return number_format($ganancia_dB,2);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calcular'])) {
    // Obtener los valores del formulario
    $pi=$_POST['pi'];
    $po=$_POST['po'];
    // Calcular la ganancia del amplificador
    $gain=clcGain($po,$pi);
?>
    <div>
        <p>Resultado: <?=$gain?> dB</p>
    </div>
<?php
    // Mostrar los resultados
    //echo "Ganancia del amplificador: " . $gain . " dB<br>";
}
?>
</body>
</html>
