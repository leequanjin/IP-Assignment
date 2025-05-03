<?php
session_start();
session_destroy(); // clears all session data
header("Location: ../views/user_login_view.php"); // or wherever your login page is
exit();