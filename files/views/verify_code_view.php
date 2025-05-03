<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<form method="POST" action="../controllers/UserController.php">
    <input type="hidden" name="action" value="verifyCode">
    <label>Enter Verification Code:</label>
    <input type="text" name="code" required>
    <button type="submit">Verify</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
