<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trabajo Colectivo</title>
    <link rel="stylesheet" href="./css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
   <header class="header">
    <nav class="navbar">
        <a href="http://localhost/php/Colectivo/">Inicio</a>
        <a href="https://www.instagram.com/codersucp/">Contactanos</a>
    </nav>
   </header>
    
    <div class="background"></div>
    <div class="container">
        <div class="item">
            <h2 class="logo"><i class='bx bxl-xing'></i>Trabajo Colectivo</h2>
            <div class="text-item">
                <h2>Bienvenido! <br><span>
                    A la pagina principal
                </span></h2>
                <p>Trabajo Colectivo 5 Semestre por Juan Garcia, Nicolas Ceballos y Camilo Castaneda</p>
                <div class="social-icon">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="https://twitter.com/Coders_UCP"><i class='bx bxl-twitter'></i></a>
                    <a href="https://www.youtube.com/@punteronulo"><i class='bx bxl-youtube'></i></a>
                    <a href="https://www.instagram.com/codersucp/"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </div>
        </div>
        <div class="login-section" style="left: 860px;">
            <div class="form-box login">
                <form action="./controller/authentication.php" method="post">
                    <h2>Iniciar Sesion</h2>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input type="text" required name="usuario">
                        <label >Nombre de Usuario</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' ></i></span>
                        <input type="password" required="required" name="contrasena">
                        <label>Contrasena</label>
                    </div>
                    <div class="remember-password">
                        <label for=""><input type="checkbox">Recordar Datos</label>
                        <a href="./model/account_recovery.php">Olvido la Contrasena?</a>
                    </div>
                    <button class="btn" name="Iniciar_Sesion">Iniciar Sesion</button>
                    <div class="create-account">
                        <p>No tienes Cuenta? <a href="#" class="register-link">Registrate aqui!</a></p>
                    </div>
                </form>
            </div>
            <div class="form-box register">
                <form action="./controller/create_account.php" method="post">

                    <h2>Crear Cuenta</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input type="text" required name="nombre">
                        <label >Nombre de Usuario</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                        <input type="text" required name="correo">
                        <label >Correo</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' ></i></span>
                        <input type="password" required name="contrasena">
                        <label>Contrasena</label>
                    </div>
                    <div class="remember-password">
                        <label for=""><input type="checkbox">Recordar Contrasena</label>
                    </div>
                    <button class="btn" name="Crear_Cuenta">Crear Cuenta</button>
                    <div class="create-account">
                        <p>Ya tienes una cuenta? <a href="#" class="login-link">Inicia Sesion aqui!</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./view/index.js"></script>
</body>

</html>
