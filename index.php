<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Home Page</title>
        <!-- bootstrap CSS link -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
        <!-- CSS file link -->
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary p-3" data-bs-theme="dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                        <ul class="navbar-nav me-auto my-2 my-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fa-solid fa-cart-shopping"></i><sup>1</sup></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Total Price:100/-</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
            <nav class="navbar navbar-expand-lg navbar-dark bg-secondary p-3">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Welcome Guest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                </ul>
            </nav>
        </header>
        <!-- Navbar -->

        <!-- Title Text -->
        <div class="bg-light p-4">
            <h3 class="text-center">Home Page</h3>
            <p class="text-center">Affordable design for every home</p>
        </div>

        <!-- Product Display -->
        <div class="container text-center p-4" >
            <div class="row">
                <!-- Products -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Add to cart</a>
                                    <a href="#" class="btn btn-secondary">View more</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Add to cart</a>
                                    <a href="#" class="btn btn-secondary">View more</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="..." class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">Card title</h5>
                                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                    <a href="#" class="btn btn-primary">Add to cart</a>
                                    <a href="#" class="btn btn-secondary">View more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidenav -->
                <div class="col-md-2 bg-secondary p-0 mb-4">
                    <ul class="navbar-nav me-auto text-center">
                        <li style="background-color: #2B3035;" class="nav-item py-2 border-bottom"><h4 class="text-light py-2">Categories</h4></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Category 1</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Category 2</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Category 3</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Category 4</a></li>
                    </ul>
                    <ul class="navbar-nav me-auto text-center">
                        <li style="background-color: #2B3035;" class="nav-item py-2 border-bottom"><h4 class="text-light py-2">Price Range</h4></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Range 1</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Range 2</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Range 3</a></li>
                        <li class="nav-item bg-secondary py-2 border-bottom"><a href="#" class="nav-link text-light">Range 4</a></li>
                    </ul>
                </div>

            </div>
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
        <?php
        ?>
        <!-- font awesome link -->
        <script src="https://kit.fontawesome.com/67a319415d.js" crossorigin="anonymous"></script>
        <!-- bootstrap JS link -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    </body>
</html>
