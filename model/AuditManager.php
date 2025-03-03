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
        // ...existing code from audit.php...
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
}
?>
