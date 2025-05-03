<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
</head>
<body>
    <h2>User Login</h2>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="../controllers/UserController.php" method="POST">
        <input type="hidden" name="action" value="login">

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account?</p>
    <a href="user_register_view.php">
        <button style="padding: 8px 15px;">Register</button>
    </a>

    <hr>
    <h3>OR</h3>
    <a href="../apis/google_oauth.php">
        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" 
             alt="Sign in with Google" 
             style="height: 40px;">
    </a>
</body>
</html>
