<?php
require_once 'AccessInterface.php';

class UserProxy implements AccessInterface {
    public function grantAccess() {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: ../views/user_login_view.php");
            exit();
        }
        return true;
    }
    public function grantAccess2() {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: views/user_login_view.php");
            exit();
        }
        return true;
    }
}
?>
