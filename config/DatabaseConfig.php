<?php
require_once 'config.php';
require_once 'SessionManager.php';

class DatabaseConfig {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            throw new Exception('Connection failed: ' . $this->connection->connect_error);
        }
        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public static function checkLoggedIn() {
        SessionManager::checkLogin();
    }

    public static function checkAdmin() {
        SessionManager::checkAdminRights();
    }
}

// Helper function
function getDBConnection() {
    return DatabaseConfig::getInstance()->getConnection();
}
?>
