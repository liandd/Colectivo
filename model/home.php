<?php
require_once '../config/DatabaseConfig.php';
SessionManager::checkLogin();

$userId = SessionManager::getUserId();
$userType = SessionManager::getUserType();

if (isset($_POST['action']) && isset($_POST['description'])) {
    $action = $_POST['action'];
    $desc = $_POST['description'];
    logUserActivity($action, $desc, $userId);
}

function logUserActivity($action, $description, $userId) {
  $conn = getDBConnection();

  if ($stmt = $conn->prepare('SELECT nombreUsuario FROM usuarios WHERE idUsuario = ?')) {
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
  }

  // Modify description based on page
  if ($description == 'Telecommunications and Circuits') {
    $description = ', entered Telecommunications and Circuits page.';
  } elseif ($description == 'Programming and Databases') {
    $description = ', entered Programming and Databases page.';
  } elseif ($description == 'References') {
    $description = ', entered References page.';
  }

  $fullDesc = ucfirst($username . $description);
  
  $stmt = $conn->prepare('INSERT INTO logs_auditoria (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, descripcionLogs_auditoria, idUsuario, nombreUsuario) VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
  $stmt->bind_param('ssis', $action, $fullDesc, $userId, $username);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}
?>

<script>
function loadIframe(url, description) {
  let iframeContent = '<iframe src="' + url + '" width="100%" height="100%" frameborder="0" allowfullscreen></iframe>';
  let mainSection = document.querySelector('.home-section');
  mainSection.innerHTML = iframeContent;
  
  let iframe = document.querySelector('.home-section iframe');
  iframe.addEventListener('load', function() {
    let iframeURL = iframe.contentWindow.location.href;
    let action;
    
    // Define actions based on URL
    if (iframeURL.includes('/view/modulation.php')) {
      action = 'Opened Modulation page';
    } else if (iframeURL.includes('/view/programming.html')) {
      action = 'Opened Programming and Databases Theory';
    } else if (iframeURL.includes('/view/spectrum.html')){
      action = 'Opened Spectrum Graph page';
    } else if (iframeURL.includes('/view/telecom.html')){
      action = 'Opened Telecommunications Theory';
    } else if (iframeURL.includes('/view/references.html')){
      action = 'Opened References page';
    } else if (iframeURL.includes('settings.php')){
      action = 'Opened User Settings (Admin)';
    } else if (iframeURL.includes('profile.php')){
      action = 'Opened User Profile (Admin)';
    } else if (iframeURL.includes('gain.php')){
      action = 'Opened Gain Calculator';
    } else if (iframeURL.includes('/controller/create_account.php')){
      action = 'Opened Create Account (Admin)';
    } else if (iframeURL.includes('history.php')){
      action = 'Opened Audit History (Admin)';
    } else if (iframeURL.includes('/controller/frequency_range.php')){
      action = 'Opened Frequency Range Query';
    } else {
      action = 'Opened Theory page';
    }

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'home.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=' + encodeURIComponent(action) + '&description=' + encodeURIComponent(description));
  });
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
      border: 2px solid #5A062F;
    }
  </style>
</head>
<body>
  <div class="sidebar close">
    <div class="logo-details">
      <i class='bx bxl-xing'></i>
      <span class="logo_name">Collective Work</span>
    </div>
    <ul class="nav-links">
      <li>
        <a href="home.php">
          <i class='bx bx-grid-alt'></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="home.php">Home</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-book-alt'></i>
            <span class="link_name">Theory</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Theory</a></li>
          <li><a href="#" onclick="loadIframe('../view/telecom.html', 'Telecommunications and Circuits')">Telecommunications & Circuits</a></li>
          <li><a href="#" onclick="loadIframe('../view/programming.html', 'Programming and Databases')">Programming & Databases</a></li>
          <li><a href="#" onclick="loadIframe('../view/references.html', 'References')">References</a></li>
        </ul>
      </li>
      <?php if ($userType == "Admin"): ?>
      <li>
        <div class="iocn-link">
          <a href="#">
            <i class='bx bx-cog'></i>
            <span class="link_name">Admin</span>
          </a>
          <i class='bx bxs-chevron-down arrow'></i>
        </div>
        <ul class="sub-menu">
          <li><a href="#" onclick="loadIframe('../controller/create_account.php', 'User Management')">Create Account</a></li>
          <li><a href="#" onclick="loadIframe('history.php', 'Audit History')">Audit History</a></li>
          <li><a href="#" onclick="loadIframe('settings.php', 'Settings')">Settings</a></li>
        </ul>
      </li>
      <?php endif; ?>
      <li>
        <div class="profile-details">
          <div class="profile-content">
            <img src="../images/<?php echo $userType == 'Admin' ? 'blandonferxxo.png' : 'profile.png'; ?>" alt="profileImg">
          </div>
          <div class="name-job">
            <div class="profile_name"><?=$_SESSION['name']?></div>
            <div class="job"><?=$userType?></div>
          </div>
          <a href="logout.php"><i class='bx bx-log-out'></i></a>
        </div>
      </li>
    </ul>
  </div>

  <section class="home-section">
    <!-- ...existing content section... -->
  </section>

  <script>
    let username = '<?php echo $_SESSION['name']?>';
    alert('Welcome ' + username);
  </script>
  <script>
    function logout() {
      let username = '<?php echo $_SESSION['name']?>';
      alert('Goodbye ' + username);
      window.location.href = 'logout.php';
    }
  </script>
  <script src="../view/home.js"></script>
</body>
</html>
