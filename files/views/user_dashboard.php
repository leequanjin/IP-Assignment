<?php
require_once '../proxy/UserProxy.php';

$access = new UserProxy();
if (!$access->grantAccess()) {
    header('Location: ../files/views/user_login_view.php');
    exit();
}

if (!empty($_SESSION['message'])) {
    echo "<p style='color:green'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

?>

<!--  Page Content (Only shown if logged in) -->
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['user']; ?></h2>

<a href="../userindex.php">
    <button style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px;">
        Go to User Home Page
    </button>
</a>
<a href="edit_profile.php">
    <button style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; margin-left: 10px;">
        Edit Profile
    </button>
</a>
<a href="logout.php">
    <button style="padding: 10px 20px; background-color: #dc3545; color: white; border: none; border-radius: 5px; margin-left: 10px;">
        Logout
    </button>
</a>

</body>
</html>
