<!-- Author     : keekeshen -->

<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<form method="POST" action="../controllers/UserController.php">
    <input type="hidden" name="action" value="verifyRegisterCode">
    <label>Enter Registration Verification Code:</label>
    <input type="text" name="code" required>
    <button type="submit">Verify & Complete Registration</button>
</form>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>
