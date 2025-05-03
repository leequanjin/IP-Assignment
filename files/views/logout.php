<?php
session_start();
session_destroy(); // clears all session data
header("Location: ../userIndex.php"); // or wherever your login page is
exit();