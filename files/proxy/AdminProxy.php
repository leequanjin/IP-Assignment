<?php
require_once 'AccessInterface.php';

class AdminProxy implements AccessInterface {
    public function grantAccess() {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
            header("Location: views/admin_login_view.php");
            exit();
        }
        return true;
    }
}
?>
