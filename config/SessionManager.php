<?php
class SessionManager {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function checkLogin() {
        self::init();
        if (!isset($_SESSION['loggedin'])) {
            header('Location: ../index.php');
            exit;
        }
    }

    public static function getUserId() {
        self::init();
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    public static function getUserType() {
        self::init();
        return isset($_SESSION['tipoUser']) ? $_SESSION['tipoUser'] : null;
    }

    public static function getUsername() {
        self::init();
        return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public static function checkAdminRights() {
        self::init();
        if (!isset($_SESSION['tipoUser']) || $_SESSION['tipoUser'] !== 'Admin') {
            header('Location: ../index.php');
            exit;
        }
    }
}
