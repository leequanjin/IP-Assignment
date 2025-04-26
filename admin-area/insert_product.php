<?php
$categoriesXml = '../xml-files/categories.xml';
$categoriesList = [];

if (file_exists($categoriesXml)) {
    $dom = new DOMDocument();
    $dom->load($categoriesXml);
    $categories = $dom->getElementsByTagName('category');

    foreach ($categories as $category) {
        $title = $category->getElementsByTagName('title')->item(0)->nodeValue;
        $categoriesList[] = $title;
    }
} else {
    $error = "Categories file not found.";
}

$productsXml = '../xml-files/products.xml';
$productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_product"])) {
    // Prepare input values
    $product_title = prepareInput($_POST["product_title"] ?? '');
    $product_desc = prepareInput($_POST["product_desc"] ?? '');
    $product_category = prepareInput($_POST["product_category"] ?? '');
    $product_price = prepareInput($_POST["product_price"] ?? '');
    $product_image = $_FILES["product_image"]["name"] ?? '';

    // Validate fields
    $hasError = false;

    if (empty($product_title)) {
        $productTitleError = "Please enter a product title.";
        $hasError = true;
    }
    if (empty($product_desc)) {
        $productDescError = "Please enter a product description.";
        $hasError = true;
    }
    if (empty($product_category) || $product_category == "Choose...") {
        $productCategoryError = "Please select a category.";
        $hasError = true;
    }
    if (empty($product_image)) {
        $productImageError = "Please upload a product image.";
        $hasError = true;
    }
    if (empty($product_price)) {
        $productPriceError = "Please enter a product price.";
        $hasError = true;
    }

    if (!$hasError) {
        // Handle image upload
        $uploadDir = 'uploads/';
        $imageFilePath = '';

        if ($_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($product_image, PATHINFO_EXTENSION);
            $uniqueFileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $fileExtension;
            $imageFilePath = $uploadDir . $uniqueFileName;

            // Move the uploaded file to the desired location
            if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $imageFilePath)) {
                $productImageError = "Failed to upload the image.";
                $hasError = true;
            }
        }

        if (!$hasError) {
            try {
                // Load or create the XML
                if (file_exists($productsXml)) {
                    $dom = new DOMDocument();
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->load($productsXml);
                    $root = $dom->documentElement;
                } else {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $root = $dom->createElement('products');
                    $dom->appendChild($root);
                }

                // Create new product node
                $newProduct = $dom->createElement('product');

                $titleElement = $dom->createElement('title', $product_title);
                $descElement = $dom->createElement('description', $product_desc);
                $categoryElement = $dom->createElement('category', $product_category);
                $priceElement = $dom->createElement('price', $product_price);
                $imageElement = $dom->createElement('image', $imageFilePath);

                $newProduct->appendChild($titleElement);
                $newProduct->appendChild($descElement);
                $newProduct->appendChild($categoryElement);
                $newProduct->appendChild($priceElement);
                $newProduct->appendChild($imageElement);

                $root->appendChild($newProduct);
                $dom->save($productsXml);

                $successMessage = "Product has been inserted successfully!";
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo "<script>alert('Something went wrong. Please try again later.');</script>";
            }
        }
    }
}

function prepareInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<form action="" method="post" enctype="multipart/form-data" class="mb-2">
    <div>
        <div class="mb-3">
            <label class="form-label">Product Title</label>
            <input type="text" name="product_title" class="form-control" placeholder="Enter Product Title">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productTitleError ? 'text-danger' : ''); ?>">
            <?php if ($productTitleError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productTitleError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="mb-3">
            <label class="form-label">Product Description</label>
            <input type="text" name="product_desc" class="form-control" placeholder="Enter Product Description">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productDescError ? 'text-danger' : ''); ?>">
            <?php if ($productDescError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productDescError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="product_category" class="form-select">
                <option selected>Choose...</option>
                <?php foreach ($categoriesList as $catTitle): ?>
                    <option value="<?php echo htmlspecialchars($catTitle); ?>">
                        <?php echo htmlspecialchars($catTitle); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productCategoryError ? 'text-danger' : ''); ?>">
            <?php if ($productCategoryError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productCategoryError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="mb-3">
            <label class="form-label">Product Image</label>
            <input type="file" name="product_image" class="form-control">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productImageError ? 'text-danger' : ''); ?>">
            <?php if ($productImageError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productImageError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="mb-3">
            <label class="form-label">Product Price (RM)</label>
            <input type="text" name="product_price" class="form-control" placeholder="Enter Product Price">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productPriceError ? 'text-danger' : ''); ?>">
            <?php if ($productPriceError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productPriceError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="input-group mb-2">
        <input type="submit" class="form-control btn btn-secondary" name="insert_product" value="Submit">
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success mt-2">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
</form>
