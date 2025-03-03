<?php
require_once '../config/config.php';
require_once '../config/DatabaseConfig.php';
require_once '../config/SessionManager.php';

class AuditLogger {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        $this->conn = getDBConnection();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log($action, $description, $userId = null, $username = null) {
        if (!AUDIT_ENABLED) return;

        if (!$userId) $userId = SessionManager::getUserId();
        if (!$username) $username = SessionManager::getUsername();

        $stmt = $this->conn->prepare('INSERT INTO logs_auditoria 
            (fechaLogs_auditoria, horaLogs_auditoria, accionLogs_auditoria, 
             descripcionLogs_auditoria, idUsuario, nombreUsuario) 
            VALUES (CURDATE(), CURTIME(), ?, ?, ?, ?)');
        
        $stmt->bind_param('ssis', $action, $description, $userId, $username);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    // Métodos específicos para diferentes tipos de auditoría
    public function logUserAction($action, $details) {
        return $this->log(
            $action,
            "User action: $details",
            SessionManager::getUserId(),
            SessionManager::getUsername()
        );
    }

    public function logSystemEvent($event, $details) {
        return $this->log(
            'System',
            "$event: $details",
            0,
            'SYSTEM'
        );
    }
}

// Helper function
function auditLog($action, $description) {
    return AuditLogger::getInstance()->log($action, $description);
}
?>
