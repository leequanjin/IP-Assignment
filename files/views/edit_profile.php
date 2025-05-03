<?php
require_once '../proxy/UserProxy.php';

$access = new UserProxy();
if (!$access->grantAccess()) {
    header('Location: ../userIndex.php');
    exit();
}

$email = $_SESSION['email'];
$xml = simplexml_load_file('../../xml-files/users.xml');
$user = null;

foreach ($xml->user as $u) {
    if ((string) $u->email === $email) {
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
        <meta charset="UTF-8">
        <title>Edit Your Profile</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="../userIndex.php">Hup Hwa Furniture</a>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item"><span class="nav-link">Welcome, <?php echo htmlspecialchars($user->name); ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="user_profile_view.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-5">
            <h3 class="text-center">Edit Your Profile</h3>

            <?php
            if (!empty($_SESSION['message'])) {
                echo "<div class='alert alert-success text-center'>" . htmlspecialchars($_SESSION['message']) . "</div>";
                unset($_SESSION['message']);
            } elseif (!empty($_SESSION['error'])) {
                echo "<div class='alert alert-danger text-center'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']);
            }
            ?>



            <div class="card mx-auto" style="max-width: 600px;">
                <div class="card-body">
                    <form action="../controllers/UserController.php" method="POST">
                        <input type="hidden" name="action" value="updateProfile">

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $user->email; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $user->name; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" value="<?php echo $user->age; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male" <?php if ($user->gender == "Male") echo "selected"; ?>>Male</option>
                                <option value="Female" <?php if ($user->gender == "Female") echo "selected"; ?>>Female</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control"><?php echo $user->address; ?></textarea>
                        </div>

                        <?php
                        $loginProvider = $_SESSION['login_provider'] ?? 'local'; // default to local login
                        if ($loginProvider !== 'google') :
                            ?>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                            </div>
                        <?php endif; ?>



                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="user_profile_view.php" class="btn btn-secondary">Back to Profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="text-center text-white bg-dark p-3 mt-5">
            © 2025 Hup Hwa Furniture Trading Sdn. Bhd
        </footer>
    </body>
</html>
