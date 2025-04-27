<?php
$categoriesXml = '../xml-files/categories.xml';
$productsXml = '../xml-files/products.xml'; // <-- NEW

if (file_exists($categoriesXml)) {
    $dom = new DOMDocument();
    $dom->load($categoriesXml);
    $categories = $dom->getElementsByTagName('category');
}

if (file_exists($productsXml)) {
    $productDom = new DOMDocument();
    $productDom->load($productsXml);
    $products = $productDom->getElementsByTagName('product');
}
?>

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
        <link href="../style.css" rel="stylesheet">
    </head>
    <body>
        <!-- Navbar -->
        <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary p-3" data-bs-theme="dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Logo</a>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                        <ul class="navbar-nav me-auto my-2 my-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="fa-solid fa-cart-shopping"></i><sup> 0</sup></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Total Price: RM 0.00</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search" method="GET" action="index.php">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
                        <?php
                        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
                        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
                        ?>
                        <?php if (!empty($products)): ?>
                            <?php
                            $productFound = false;
                            foreach ($products as $product):
                                $title = $product->getElementsByTagName('title')->item(0)->nodeValue;
                                $description = $product->getElementsByTagName('description')->item(0)->nodeValue;
                                $price = $product->getElementsByTagName('price')->item(0)->nodeValue;
                                $imageNode = $product->getElementsByTagName('image')->item(0);
                                $image = $imageNode ? $imageNode->nodeValue : 'no-image.jpg';
                                $category = $product->getElementsByTagName('category')->item(0)->nodeValue;

                                if ($selectedCategory && $selectedCategory !== $category) {
                                    continue;
                                }

                                if ($searchQuery && stripos($title, $searchQuery) === false) {
                                    continue;
                                }

                                $productFound = true;

                                $imagePath = '../admin-area/uploads/' . $image;
                                ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card" style="width: 18rem;">
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" loading="lazy" class="card-img-top" alt="Product Image">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($description); ?></p>
                                            <p class="card-text fw-bold">$<?php echo htmlspecialchars($price); ?></p>
                                            <a href="#" class="btn btn-primary">Add to cart</a>
                                            <a href="#" class="btn btn-secondary">View more</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (!$productFound): ?>
                                <p>No products found.</p>
                            <?php endif; ?>

                        <?php else: ?>
                            <p>No products available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Sidenav -->
                <div class="col-md-2 bg-secondary p-0 mb-4">
                    <ul class="navbar-nav me-auto text-center">
                        <li style="background-color: #2B3035;" class="nav-item py-2 border-bottom">
                            <h4 class="text-light py-2">Categories</h4>
                        </li>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <?php $title = $category->getElementsByTagName('title')->item(0)->nodeValue; ?>
                                <li class="nav-item bg-secondary py-2 border-bottom">
                                    <a href="index.php?category=<?php echo urlencode($title); ?>" class="nav-link text-light"><?php echo htmlspecialchars($title); ?></a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="nav-item bg-secondary py-2 border-bottom">
                                <a href="#" class="nav-link text-light">No categories available.</a>
                            </li>
                        <?php endif; ?>
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
        <?php ?>
        <!-- font awesome link -->
        <script src="https://kit.fontawesome.com/67a319415d.js" crossorigin="anonymous"></script>
        <!-- bootstrap JS link -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    </body>
</html>
