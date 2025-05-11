<!-- Author     : keekeshen -->

<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<form method="POST" action="../controllers/UserController.php">
    <input type="hidden" name="action" value="sendResetCode">
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send Verification Code</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
