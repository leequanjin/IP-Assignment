<!-- Author     : keekeshen -->

<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<form method="POST" action="../controllers/UserController.php">
    <input type="hidden" name="action" value="resetPassword">
    <label>New Password:</label>
    <input type="password" name="password" required>
    <button type="submit">Set New Password</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
