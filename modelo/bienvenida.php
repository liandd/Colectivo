<?php
session_start();
// Si el usuario no esta logeado:
if (!isset($_SESSION['loggedin'])) {
	header('Location: ./index.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="../css/dashboard.css" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        <a href="#">
          <i class='bx bx-grid-alt' ></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Categoria</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-collection' ></i>
            <span class="link_name">Parte Informativa</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Parte Informativa</a></li>
          <li><a href="#">Fundamento Teorico</a></li>
          <li><a href="#">Entrega 2</a></li>
          <li><a href="#">Entrega 3</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt' ></i>
            <span class="link_name">Haz tus calculos</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Parte Practica</a></li>
          <li><a href="#">Modulacion</a></li>
          <li><a href="#">Rango de Frecuencias</a></li>
        </ul>
      </li>
      <li>
        <a href="perfil.php">
          <i class='bx bx-cog' ></i>
          <span class="link_name">Ajustes</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Ajustes</a></li>
        </ul>
      </li>
      <li>
    <div class="profile-details">
      <div class="profile-content">
        <img src="http://localhost/php/Colectivo/images/blandonferxxo.png" alt="profileImg">
      </div>
      <div class="name-job">
        <div class="profile_name"><?=$_SESSION['name']?></div>
        <div class="job">Usuario</div>
      </div>
      <a href="#" onclick="salir()">
        <i class='bx bx-log-out'></i>
    </a>
    </div>
  </li>
</ul>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Dashboard</span>
    </div>
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