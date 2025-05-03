<!-- Author     : keekeshen -->
<?php
require_once '../proxy/UserProxy.php';

$access = new UserProxy();
if (!$access->grantAccess()) {
    header('Location: ../userIndex.php');
    exit();
}

$username = $_SESSION['user'];
$email = $_SESSION['email']; // This should match the <name> field

// Load XML file
$xml = simplexml_load_file('../../xml-files/users.xml');
$userData = null;

foreach ($xml->user as $user) {
    if ((string)$user->email === $email) {
        $userData = $user;
        break;
    }
}

if (!$userData) {
    die("User data not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="../userIndex.php">Hup Hwa Furniture</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item"><span class="nav-link">Welcome, <?php echo htmlspecialchars($username); ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="text-center">Your Profile</h3>
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($userData->name); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($userData->age); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($userData->gender); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($userData->email); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($userData->address); ?></p>
                <a href="edit_profile.php" class="btn btn-secondary">Edit Details</a>
            </div>
        </div>
    </div>

    <footer class="text-center text-white bg-dark p-3 mt-5">
        © 2025 Hup Hwa Furniture Trading Sdn. Bhd
    </footer>
</body>
</html>
