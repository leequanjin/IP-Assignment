<?php
require_once '../proxy/AdminProxy.php';

$access = new AdminProxy();
if (!$access->grantAccess()) {
    header('Location: ../views/admin_login_view.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Admin Panel - Welcome, <?php echo $_SESSION['admin']; ?></h2>

<a href="../adminindex.php">
    <button style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px;">
        Go to Admin Area
    </button>
</a>

<a href="admin_logout.php">
    <button style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 5px; margin-left: 10px;">
        Logout
    </button>
</a>

</body>
</html>
