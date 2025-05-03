<!-- Author     : Lee Quan Jin -->

<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <meta http-equiv = "X-UA-Compatible" content = "ie=edge">
        <title>Welcome to Hup Hwa Furniture Store</title>
        <!--bootstrap CSS link-->
        <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel = "stylesheet" integrity = "sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin = "anonymous">
        <!--CSS file link-->
        <link href = "../style.css" rel = "stylesheet">
    </head>
    <body>

        <header>
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
            <nav class = "navbar navbar-expand-lg bg-body-tertiary p-3" data-bs-theme = "dark">
                <div class = "container-fluid">
                    <button class = "navbar-toggler" type = "button" data-bs-toggle = "collapse" data-bs-target = "#navbarTogglerDemo03" aria-controls = "navbarTogglerDemo03" aria-expanded = "false" aria-label = "Toggle navigation">
                        <span class = "navbar-toggler-icon"></span>
                    </button>
                    <div class = "collapse navbar-collapse" id = "navbarTogglerDemo03">
                        <ul class = "navbar-nav me-auto my-2 my-lg-0">
                            <li class = "nav-item">
                                <a class = "nav-link active" aria-current = "page" href = "userIndex.php">Home</a>
                            </li>
                            <li class = "nav-item">
                                <a class = "nav-link" href = "userIndex.php?module=cart&action=view">View Cart</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search" method="GET" action="userIndex.php">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </form>
                        <form class="d-flex ms-2" method="GET" action="userIndex.php">
                            <?php
                            foreach ($_GET as $key => $value) {
                                if ($key !== 'currency') {
                                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                }
                            }
                            ?>
                            <select class="form-select me-2" name="currency">
                                <?php $selectedCurrency = $_GET['currency'] ?? 'MYR'; ?>
                                <option value="MYR" <?php echo $selectedCurrency === 'MYR' ? 'selected' : ''; ?>>MYR</option>
                                <option value="USD" <?php echo $selectedCurrency === 'USD' ? 'selected' : ''; ?>>USD</option>
                            </select>
                            <button class="btn btn-outline-secondary" type="submit">Change</button>
                        </form>

                    </div>
                </div>
            </nav>
        </header>



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
                exit();
            } else {
                require_once 'controllers/UserIndexController.php';
                $controller = new UserIndexController();
                $controller->index();
                exit();
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
