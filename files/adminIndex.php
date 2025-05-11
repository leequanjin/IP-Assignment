<!-- Author     : Lee Quan Jin -->

<?php
require_once 'proxy/AdminProxy.php';

$access = new AdminProxy();
if (!$access->grantAccess()) {
    header('Location: views/admin_login_view.php');
    exit();
}

$timeout_duration = 10;

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: views/admin_login_view.php?timeout=1");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Admin Dashboard</title>
        <!-- bootstrap CSS link -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- CSS file link -->
        <link href="../style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg bg-dark p-2" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Hup Hwa Furniture</a>
                    <nav class="navbar navbar-expand-lg">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link">Welcome <?php echo htmlspecialchars($_SESSION['admin']); ?></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </nav>
        </div>

        <div class="bg-light p-3">
            <h3 class="text-center">Admin Dashboard</h3>
        </div>

        <div class="row">
            <div class="col-md-12 bg-secondary p-2"> 
                <div class="button text-center">
                    <button class="btn btn-secondary"><a href="adminIndex.php?module=product&action=insert" class="nav-link">Insert Product</a></button>
                    <button class="btn btn-secondary"><a href="adminIndex.php?module=product&action=view" class="nav-link">View Product</a></button>
                    <button class="btn btn-secondary"><a href="adminIndex.php?module=category&action=insert" class="nav-link">Insert Categories</a></button>
                    <button class="btn btn-secondary"><a href="adminIndex.php?module=category&action=view" class="nav-link">View Categories</a></button>
                    <button class="btn btn-secondary"><a href="views/list_users.php" class="nav-link">List Users</a></button>
                    <button class="btn btn-secondary"><a href="views/admin_logout.php" class="nav-link">Logout</a></button>
                </div>
            </div>  
        </div>

        <div class="container my-5">
            <?php
            $module = $_GET['module'] ?? null;
            $action = $_GET['action'] ?? null;
            if ($module === 'category') {
                require_once 'controllers/CategoryController.php';
                $controller = new CategoryController();
                $controller->handleRequest($action);
            } elseif ($module === 'product') {
                require_once 'controllers/ProductController.php';
                $controller = new ProductController();
                $controller->handleRequest($action);
            } elseif ($module === 'user') {
                include 'controllers/UserController.php';
            } else {
                echo '<p class="text-center">Select an option above to continue.</p>';
            }
            ?>
        </div>

        <!-- Footer -->
        <footer class="text-center text-white" style="background-color: #2B3035;">
            <div class="text-center p-3">
                © 2025 Copyright: Hup Hwa Furniture Trading Sdn. Bhd
            </div>
        </footer>

        <!-- font awesome -->
        <script src="https://kit.fontawesome.com/67a319415d.js" crossorigin="anonymous"></script>
        <!-- bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

        <script>
            let timeoutDuration = 10 * 60 * 1000;
            let logoutTimer;

            function resetLogoutTimer() {
                clearTimeout(logoutTimer);
                logoutTimer = setTimeout(() => {
                    window.location.href = "views/admin_logout.php?timeout=1";
                }, timeoutDuration);
            }

            window.onload = resetLogoutTimer;
            document.onmousemove = resetLogoutTimer;
            document.onkeypress = resetLogoutTimer;
            document.onscroll = resetLogoutTimer;
            document.onclick = resetLogoutTimer;
        </script>
    </body>
</html>
