<div class="container text-center p-4" >
    <div class="row">
        <!-- Products -->
        <div class="col-md-10">
            <div class="row">
                <?php if (!empty($products)): ?>
                    <?php
                    $productFound = false;
                    foreach ($products as $product):
                        $id = $product->getElementsByTagName('id')->item(0)->nodeValue;
                        $title = $product->getElementsByTagName('title')->item(0)->nodeValue;
                        $description = $product->getElementsByTagName('description')->item(0)->nodeValue;
                        $price = $product->getElementsByTagName('price')->item(0)->nodeValue;
                        $imageNode = $product->getElementsByTagName('image')->item(0);
                        $image = $imageNode ? $imageNode->nodeValue : 'no-image.jpg';
                        $category = $product->getElementsByTagName('category')->item(0)->nodeValue;

                        if ($selectedCategory && $selectedCategory !== $category) {
                            continue;
                        }

                        if ($search && stripos($title, $search) === false) {
                            continue;
                        }

                        $currencyConverter = new CurrencyConverter();
                        $selectedCurrency = $_GET['currency'] ?? 'MYR';
                        $convertedPrice = $price;

                        if ($selectedCurrency !== 'MYR') {
                            $convertedPrice = $currencyConverter->convertCurrency($price, 'MYR', $selectedCurrency);
                        }

                        $productFound = true;

                        $imagePath = '../files/uploads/' . $image;
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card" style="width: 18rem;">
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" loading="lazy" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($title); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($description); ?></p>
                                    <p class="card-text fw-bold">
                                        <?php echo htmlspecialchars($selectedCurrency); ?> <?php echo number_format($convertedPrice, 2); ?>
                                    </p>
                                    <a href="userIndex.php?module=cart&action=add&add_to_cart=<?php echo $id; ?>" class="btn btn-secondary">Add to cart</a>
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
                            <a href="userIndex.php?category=<?php echo urlencode($title); ?>" class="nav-link text-light"><?php echo htmlspecialchars($title); ?></a>
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