<?php
session_start();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>Register Staff</h2>

<form action="../controllers/AdminController.php" method="POST">
    <input type="hidden" name="action" value="register">
    Name: <input type="text" name="name"><br>
    Age: <input type="number" name="age"><br>
    Gender: 
    <select name="gender">
        <option>Male</option>
        <option>Female</option>
    </select><br>
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit">Register Admin</button>
</form>

<?php if (!empty($message)): ?>
    <p style="color: <?= strpos($message, 'successful') !== false ? 'green' : 'red' ?>;">
        <?= $message ?>
    </p>

    <?php if (strpos($message, 'successful') !== false): ?>
        <a href="admin_login_view.php">
            <button style="padding: 10px 20px;">Proceed to Login</button>
        </a>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>    