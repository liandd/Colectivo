<?php
require_once '../config/config.php';
require_once '../config/DatabaseConfig.php';
require_once '../config/SessionManager.php';

class AuditManager {
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
        return $stmt->execute();
    }

    public function getAuditHistory($limit = 10) {
        $sql = "SELECT * FROM logs_auditoria 
                ORDER BY fechaLogs_auditoria DESC, horaLogs_auditoria DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Métodos de utilidad
    public function logUserAction($action, $details) {
        return $this->log(
            $action,
            "User action: $details",
            SessionManager::getUserId(),
            SessionManager::getUsername()
        );
    }

    public function logSystemEvent($event, $details) {
        return $this->log('System', "$event: $details", 0, 'SYSTEM');
    }
}

// Helper function global
function auditLog($action, $description) {
    return AuditManager::getInstance()->log($action, $description);
}
?>
