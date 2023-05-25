<?php
session_start();
// Si el usuario no esta logeado:
if (!isset($_SESSION['loggedin'])) {
	header('Location: ./index.php');
	exit;
}
?>
<script>
  function cargarIframe(url){
    let contenidoIframe='<iframe src="'+url+'" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';
    let seccionPrincipal = document.querySelector('.home-section');
    seccionPrincipal.innerHTML = contenidoIframe;
  }
</script>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="../css/dashboard.css" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
    body {
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    table {
      margin: auto;
    }
    td {
      padding: 10px;
      border: 2px solid black;
      border-color: #5A062F;
    }
  </style>
   </head>
<body>
  <div class="sidebar close">
    <div class="logo-details">
      <i class='bx bxl-xing'></i>
      <span class="logo_name">
        Trabajo
         Colectivo</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="inicio.php">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="inicio.php">Inicio</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt' ></i>
            <span class="link_name">Marco⠀teórico</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Marco teórico</a></li>
          <li><a href="#" onclick="cargarIframe('../vista/teleco.html')">Telecomunicaciones y Circuitos</a></li>
          <li><a href="#" onclick="cargarIframe('../vista/progra.html')">Programación y Bases de datos</a></li>
          <li><a href="#" onclick="cargarIframe('../vista/referencias.html')">Referencias</a></li>
        </ul>
      </li>
      <li>
        <a href="#" onclick="cargarIframe('../vista/modulacion.html')">
          <i class='bx bx-pie-chart-alt-2' ></i>
          <span class="link_name">Modulacion</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#" onclick="cargarIframe('../vista/modulacion.html')">Modulación</a></li>
        </ul>
      </li>
      <li>
        <a href="#" onclick="cargarIframe('../modelo/ganancia.php')">
          <i class=' bx bx-bar-chart-alt-2'></i>
          <span class="link_name">Ganancia</span>
        </a>
        <ul class="sub-menu blank">
          <li><a href="#" class="link_name" onclick="cargarIframe('../modelo/ganancia.php')">Calcular Ganancia</a></li>
        </ul>
      </li>
      <li>
        <a href="#" onclick="cargarIframe('../controlador/consultarRango.php')">
          <i class='bx bx-line-chart' ></i>
          <span class="link_name">Rango de Frecuencias</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#" onclick="cargarIframe('../controlador/consultarRango.php')">Rango de Frecuencias</a></li>
        </ul>
      </li>
      <li>
        <a href="#">
          <i class='bx bx-history'></i>
          <span class="link_name">Historial</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Historial</a></li>
        </ul>
      </li>
      <li>
        <a href="#" onclick="cargarIframe('./perfil.php')">
          <i class='bx bx-cog' ></i>
          <span class="link_name">Ajustes</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#" onclick="cargarIframe('./ajustes.php')">Ajustes</a></li>
        </ul>
      </li>
      <li>
    <div class="profile-details">
      <div class="profile-content">
        <img src="http://localhost/php/Colectivo/images/blandonferxxo.png" alt="profileImg">
      </div>
      <div class="name-job">
        <div class="profile_name"><?=$_SESSION['name']?></div>
        <div class="job">Admin</div>
      </div>
      <a href="salir.php"><i class='bx bx-log-out'></i></a>
    </div>
  </li>
</ul>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Dashboard</span>
    </div>
    <section class="content">
    <h2 style="text-align: center;">Implementación de un transmisor FM y otras generalidades del espectro electromagnético</h2>
    <h3 style="text-align: center;">FM transmitter implementation and other electromagnetic spectrum generalities</h3>
    <p style="text-align: center;">C.Castañeda, N. Ceballos, J. Garcia <br>
       Universidad Católica de Pereira
    </p>
    <table>
  <tr>
    <td>
      <img src="../images/Foto2.jpeg" alt="Imagen 1" style="width: 478px; height: 378px;">
    </td>
    <td>
      <img src="../images/Foto3.jpeg" alt="Imagen 2" style="width: 478px; height: 378px;">
    </td>
  </tr>
  <tr>
    <td>
      <img src="../images/Foto4.jpeg" alt="Imagen 3" style="width: 478px; height: 358px;">
    </td>
    <td>
      <img src="../images/Foto5.jpeg" alt="Imagen 4" style="width: 478px; height: 358px;">
    </td>
  </tr>
</table>
  </section>
  </section>
  <script>
    let n='<?php echo $_SESSION['name']?>';
    alert('Bienvenido '+n);
  </script>
  <script>
  function salir() {
    let n = '<?php echo $_SESSION['name']?>';
    alert('Adiós ' + n);
    window.location.href = 'salir.php';}
  </script>
  <script src="../vista/inicio.js"></script>
</body>
</html>