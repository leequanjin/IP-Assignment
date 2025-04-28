<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Admin Dashboard</title>
        <!-- bootstrap CSS link -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
        <!-- CSS file link -->
        <link href="../style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <div class="container-fluid p-0">
            <nav class="navbar navbar-expand-lg bg-body-tertiary p-2" data-bs-theme="dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Logo</a>
                    <nav class="navbar navbar-expand-lg">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link">Welcome guest</a>
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
                    <button class="btn btn-secondary"><a href="index.php?insert_product" class="nav-link">Insert Product</a></button>
                    <button class="btn btn-secondary"><a href="index.php?view_product" class="nav-link">View Product</a></button>
                    <button class="btn btn-secondary"><a href="index.php?insert_category" class="nav-link">Insert Categories</a></button>
                    <button class="btn btn-secondary"><a href="index.php?view_category" class="nav-link">View Categories</a></button>
                    <button class="btn btn-secondary"><a href="index.php?all_order" class="nav-link">All Orders</a></button>
                    <button class="btn btn-secondary"><a href="index.php?all_payment" class="nav-link">All Payments</a></button>
                    <button class="btn btn-secondary"><a href="index.php?list_user" class="nav-link">List Users</a></button>
                    <button class="btn btn-secondary"><a href="" class="nav-link">Logout</a></button>
                </div>
            </div>  
        </div>
        <!-- Navbar -->

        <div class="container my-5">
            <?php
            if (isset($_GET['insert_product'])) {
                include('./insert_product.php');
            } else if (isset($_GET['view_product'])) {
                include('./view_product.php');
            } else if (isset($_GET['edit_product'])) {
                include('./edit_product.php');
            } else if (isset($_GET['delete_product'])) {
                include('./delete_product.php');
            } else if (isset($_GET['insert_category'])) {
                include('./insert_category.php');
            } else if (isset($_GET['view_category'])) {
                include('./view_category.php');
            } else if (isset($_GET['delete_category'])) {
                include('./delete_category.php');
            }
            ?>
        </div>

        <!-- Footer -->
        <footer class="text-center text-white" style="background-color: #2B3035;">
            <!-- Copyright -->
            <div class="text-center p-3">
                © 2025 Copyright: Hup Hwa Furniture Trading Sdn. Bhd
            </div>
            <!-- Copyright -->
        </footer>
        <!-- Footer -->

        <!-- font awesome link -->
        <script src="https://kit.fontawesome.com/67a319415d.js" crossorigin="anonymous"></script>
        <!-- bootstrap JS link -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    </body>
</html>
