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
$productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_product"])) {
    $product_title = prepareInput($_POST["product_title"]);
    $product_desc = prepareInput($_POST["product_desc"]);
    $product_category = prepareInput($_POST["product_category"]);
    $product_image = $_FILES["product_image"];
    $product_price = prepareInput($_POST["product_price"]);
    $product_stock = prepareInput($_POST["product_stock"]);

    $hasError = false;

    if (!$product_title) {
        $productTitleError = "Please enter a product title.";
        $hasError = true;
    }
    if (!$product_desc) {
        $productDescError = "Please enter a product description.";
        $hasError = true;
    }
    if (!$product_category || $product_category == "Choose...") {
        $productCategoryError = "Please select a category.";
        $hasError = true;
    }
    if (!$product_price) {
        $productPriceError = "Please enter a product price.";
        $hasError = true;
    } elseif (!is_numeric($product_price) || $product_price < 0) {
        $productPriceError = "Please enter a valid positive number.";
        $hasError = true;
    }
    if (!$product_stock) {
        $productStockError = "Please enter available stock.";
        $hasError = true;
    }
    if (!$product_image || $product_image['error'] !== UPLOAD_ERR_OK) {
        $productImageError = "Please upload a product image.";
        $hasError = true;
    } else {
        // Check the file extension
        $fileExtension = strtolower(pathinfo($product_image['name'], PATHINFO_EXTENSION));
        if ($fileExtension !== 'png') {
            $productImageError = "Only PNG extension files are allowed.";
            $hasError = true;
        }

        // Check the MIME type
        $mimeType = mime_content_type($product_image['tmp_name']);
        if ($mimeType !== 'image/png') {
            $productImageError = "Only PNG MIME type files are allowed.";
            $hasError = true;
        }

        // Check if it's a valid image using getimagesize()
        $imageInfo = getimagesize($product_image['tmp_name']);
        if ($imageInfo === false) {
            $productImageError = "Uploaded file is not a valid image.";
            $hasError = true;
        }
    }

    if (!$hasError) {
        $imageFileName = '';
        $newFileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $fileExtension;
        $uploadPath = 'uploads/' . $newFileName;

        if (!move_uploaded_file($product_image['tmp_name'], $uploadPath)) {
            $productImageError = "Error uploading the image.";
            $hasError = true;
        } else {
            $imageFileName = $newFileName;
        }

        if (!$hasError) {
            try {
                if (file_exists($productsXml)) {
                    $dom = new DOMDocument();
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->load($productsXml);
                    $root = $dom->documentElement;

                    $products = $dom->getElementsByTagName('product');
                    $maxId = 0;
                    foreach ($products as $product) {
                        $id = (int) $product->getElementsByTagName('id')->item(0)->nodeValue;
                        if ($id > $maxId) {
                            $maxId = $id;
                        }
                    }
                    $product_id = $maxId + 1;
                } else {
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $root = $dom->createElement('products');
                    $dom->appendChild($root);

                    $product_id = 1;
                }

                $newProduct = $dom->createElement('product');

                $idElement = $dom->createElement('id', $product_id);
                $titleElement = $dom->createElement('title', $product_title);
                $descElement = $dom->createElement('description', $product_desc);
                $categoryElement = $dom->createElement('category', $product_category);
                $priceElement = $dom->createElement('price', $product_price);
                $stockElement = $dom->createElement('stock', $product_stock);
                $imageElement = $dom->createElement('image', $imageFileName);

                $newProduct->appendChild($idElement);
                $newProduct->appendChild($titleElement);
                $newProduct->appendChild($descElement);
                $newProduct->appendChild($categoryElement);
                $newProduct->appendChild($priceElement);
                $newProduct->appendChild($stockElement);
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
    <div class="mb-3">
        <label class="form-label">Product Title</label>
        <input type="text" name="product_title" class="form-control" placeholder="Enter Product Title">
        <div class="form-text m-1 mb-3 <?php echo ($productTitleError ? 'text-danger' : ''); ?>">
            <?php if ($productTitleError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productTitleError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Description</label>
        <input type="text" name="product_desc" class="form-control" placeholder="Enter Product Description">
        <div class="form-text m-1 mb-3 <?php echo ($productDescError ? 'text-danger' : ''); ?>">
            <?php if ($productDescError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productDescError; ?>
            <?php endif; ?>
        </div>
    </div>

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
        <div class="form-text m-1 mb-3 <?php echo ($productCategoryError ? 'text-danger' : ''); ?>">
            <?php if ($productCategoryError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productCategoryError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <div class="d-flex flex-column">
            <img id="preview_1" src="uploads/no-image.jpg" 
                 alt="Placeholder" width="150" height="150" 
                 class="mb-2 rounded shadow-sm" style="object-fit: cover;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('product_image').click();">
                Choose Image
            </button>

            <input type="file" name="product_image" id="product_image" style="display: none;" onchange="previewImage(event, 1);">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productImageError ? 'text-danger' : ''); ?>">
            <?php if ($productImageError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productImageError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Price (RM)</label>
        <input type="text" name="product_price" class="form-control" placeholder="Enter Product Price">
        <div class="form-text m-1 mb-3 <?php echo ($productPriceError ? 'text-danger' : ''); ?>">
            <?php if ($productPriceError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productPriceError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Available Stock</label>
        <input type="text" name="product_stock" class="form-control" placeholder="Enter Available Stock">
        <div class="form-text m-1 mb-3 <?php echo ($productStockError ? 'text-danger' : ''); ?>">
            <?php if ($productStockError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productStockError; ?>
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

<script>
    function previewImage(event, index) {
        const input = event.target;
        const preview = document.getElementById('preview_' + index);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
