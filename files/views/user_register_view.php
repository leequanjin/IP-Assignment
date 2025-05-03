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

<h2>Register Account</h2>

<form action="../controllers/UserController.php" method="POST">
    <input type="hidden" name="action" value="register">
    Name: <input type="text" name="name" required><br>
    Age: <input type="number" name="age" required><br>
    Gender: 
    <select name="gender">
        <option>Male</option>
        <option>Female</option>
    </select><br>
    Email: <input type="email" name="email" required><br>
    Address: <textarea name="address" required></textarea><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Register</button>
</form>

<?php if (!empty($message)): ?>
    <p style="color: <?= strpos($message, 'successful') !== false ? 'green' : 'red' ?>;">
        <?= $message ?>
    </p>

    <?php if (strpos($message, 'successful') !== false): ?>
        <a href="user_login_view.php">
            <button style="padding: 10px 20px;">Proceed to Login</button>
        </a>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
