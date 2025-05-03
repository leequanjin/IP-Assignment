<?php
require_once 'AccessInterface.php';

class UserProxy implements AccessInterface {
    public function grantAccess() {
        session_start();
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: ../userIndex.php");
            exit();
        }
        return true;
    }
}
?>
