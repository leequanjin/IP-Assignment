<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Hup Hwa Furniture Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hup Hwa Furniture</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/user_profile_view.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="views/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="nav-link text-white">Welcome Guest</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/user_login_view.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Welcome Section -->
<div class="container text-center mt-5">
    <h1>Welcome to Hup Hwa Furniture Store</h1>
    <p>Affordable design for every home.</p>
</div>

<div class="container my-5">
            <?php
            $module = $_GET['module'] ?? null;
            $action = $_GET['action'] ?? null;
            if ($module === 'cart') {
                require_once 'controllers/CartController.php';
                $controller = new CartController();
                $controller->handleRequest($action);
            } else {
                require_once 'controllers/UserIndexController.php';
                $controller = new UserIndexController();
                $controller->index();
            }
            ?>
        </div>

        <footer class="text-center text-white" style="background-color: #2B3035;">
            <div class="text-center p-3">
                © 2025 Copyright: Hup Hwa Furniture Trading Sdn. Bhd
            </div>
        </footer>
        <!-- font awesome link -->
        <script src="https://kit.fontawesome.com/67a319415d.js" crossorigin="anonymous"></script>
        <!-- bootstrap JS link -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

</body>
</html>
