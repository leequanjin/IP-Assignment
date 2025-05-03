<?php
require_once '../models/UserModel.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: user_login_view.php');
    exit();
}

$email = $_SESSION['email'];
$xml = simplexml_load_file('../../xml-files/users.xml');
$user = null;

foreach ($xml->user as $u) {
    if ((string)$u->email === $email) {
        $user = $u;
        break;
    }
}

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>

<h2>Edit Your Profile</h2>

<?php
if (!empty($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}
?>

<form action="../controllers/UserController.php" method="POST">
    <input type="hidden" name="action" value="updateProfile">
    Email: <input type="email" name="email" value="<?php echo $user->email; ?>" readonly><br><br>
    Name: <input type="text" name="name" value="<?php echo $user->name; ?>"><br>
    Age: <input type="number" name="age" value="<?php echo $user->age; ?>"><br>
    Gender: 
    <select name="gender">
        <option value="Male" <?php if ($user->gender == "Male") echo "selected"; ?>>Male</option>
        <option value="Female" <?php if ($user->gender == "Female") echo "selected"; ?>>Female</option>
    </select><br>
    Address: <textarea name="address"><?php echo $user->address; ?></textarea><br>
    New Password: <input type="password" name="password" placeholder="Leave blank to keep current"><br>
    <button type="submit">Save Changes</button>
</form>

<a href="user_dashboard.php">
    <button>Back to Dashboard</button>
</a>

</body>
</html>
