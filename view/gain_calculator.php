<!DOCTYPE html>
<html>
<head>
    <title>Amplifier Gain Calculator</title>
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
            border: none; 
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
        <h1>Amplifier Gain Calculator</h1>
    </header>
    <form method="POST" action="">
        <label for="pi">Input Power (Watts):</label>
        <input type="float" id="pi" name="pi" required>
        <br>
        <label for="po">Output Power (Watts):</label>
        <input type="float" id="po" name="po" required>
        <input type="submit" name="calculate" value="Calculate">
    </form>
    <div></div>

<?php
function calcGain($outputPower, $inputPower) {
    $gain_dB = 10 * log10($outputPower / $inputPower);
    return number_format($gain_dB, 2);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calculate'])) {
    $pi = $_POST['pi'];
    $po = $_POST['po'];
    $gain = calcGain($po, $pi);
?>
    <div>
        <p>Result: <?=$gain?> dB</p>
    </div>
<?php
}
?>
</body>
</html>
