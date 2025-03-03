<?php
require_once '../../model/AuditManager.php';
SessionManager::checkLogin();

$auditManager = AuditManager::getInstance();
$resultLogs = $auditManager->getAuditHistory();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit History</title>
    <link rel="stylesheet" href="../../css/settings.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    // ...existing code for scripts...
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>
<body class="loggedin">
    <nav class="navtop">
        // ...existing code for navigation...
    </nav>
    <div class="content">
        <h2>Audit History</h2>
        <div>
            <?php if ($resultLogs->num_rows > 0) : ?>
                <table id="user-table" class="styled-table">
                    <tr>
                        <th>Log ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Username</th>
                        <th>User ID</th>
                    </tr>
                    <?php while ($row = $resultLogs->fetch_assoc()) : ?>
                        // ...existing code for table rows...
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p>No audit records found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
