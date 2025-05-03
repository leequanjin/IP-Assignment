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

<?php
session_start();
if (!empty($_SESSION['message'])) {
    echo "<p style='color:red'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>
