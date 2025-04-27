<?php
require_once '../product-observer/Subject.php';
require_once '../product-observer/ProductSubject.php';
require_once '../product-observer/Observer.php';
require_once '../product-observer/ProductInventoryObserver.php';

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
$productData = [
    'title' => '',
    'desc' => '',
    'category' => '',
    'price' => '',
    'stock' => '',
    'image' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit_product"])) {
    $product_id = (int) $_GET["edit_product"];
    if (file_exists($productsXml)) {
        $dom = new DOMDocument();
        $dom->load($productsXml);
        $products = $dom->getElementsByTagName('product');

        foreach ($products as $product) {
            $id = (int) $product->getElementsByTagName('id')->item(0)->nodeValue;
            if ($id === $product_id) {
                $productData['title'] = $product->getElementsByTagName('title')->item(0)->nodeValue;
                $productData['desc'] = $product->getElementsByTagName('description')->item(0)->nodeValue;
                $productData['category'] = $product->getElementsByTagName('category')->item(0)->nodeValue;
                $productData['price'] = $product->getElementsByTagName('price')->item(0)->nodeValue;
                $productData['stock'] = $product->getElementsByTagName('stock')->item(0)->nodeValue;
                $productData['image'] = $product->getElementsByTagName('image')->item(0)->nodeValue;
                break;
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_product"])) {
    $product_id = (int) ($_POST['product_id'] ?? 0);
    $product_title = prepareInput($_POST["product_title"]);
    $product_desc = prepareInput($_POST["product_desc"]);
    $product_category = prepareInput($_POST["product_category"]);
    $product_image_old = ($_POST["product_image_old"]);
    $product_image = $_FILES["product_image" ?? null];
    $product_price = prepareInput($_POST["product_price"]);
    $product_stock = prepareInput($_POST["product_stock"]);

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
    if (empty($product_price)) {
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
    if ($product_image && $product_image['error'] === UPLOAD_ERR_OK) {
        // Check the file extension
        $fileExtension = strtolower(pathinfo($product_image['name'], PATHINFO_EXTENSION));
        if ($fileExtension !== 'png') {
            $productImageError = "Only PNG files are allowed.";
            $hasError = true;
        }

        // Check the MIME type
        $mimeType = mime_content_type($product_image['tmp_name']);
        if ($mimeType !== 'image/png') {
            $productImageError = "Only PNG files are allowed.";
            $hasError = true;
        }

        // Check if it's a valid image using getimagesize()
        $imageInfo = getimagesize($product_image['tmp_name']);
        if ($imageInfo === false) {
            $productImageError = "Uploaded file is not a valid image.";
            $hasError = true;
        }
    }

    $imageFileName = '';

    if (!$hasError) {
        if ($product_image && $product_image['error'] === UPLOAD_ERR_OK) {
            $newFileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $fileExtension;
            $uploadPath = 'uploads/' . $newFileName;

            if (!move_uploaded_file($product_image['tmp_name'], $uploadPath)) {
                $productImageError = "Error uploading the image.";
                $hasError = true;
            } else {
                $imageFileName = $newFileName;
                unlink('uploads/' . $product_image_old);
            }
        } else {
            $imageFileName = $product_image_old;
        }
    }

    if (!$hasError) {
        try {
            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->load($productsXml);
            $products = $dom->getElementsByTagName('product');

            foreach ($products as $product) {
                $id = (int) $product->getElementsByTagName('id')->item(0)->nodeValue;
                if ($id === $product_id) {
                    $product->getElementsByTagName('title')->item(0)->nodeValue = $product_title;
                    $product->getElementsByTagName('description')->item(0)->nodeValue = $product_desc;
                    $product->getElementsByTagName('category')->item(0)->nodeValue = $product_category;
                    $product->getElementsByTagName('price')->item(0)->nodeValue = $product_price;
                    $product->getElementsByTagName('stock')->item(0)->nodeValue = $product_stock;
                    $product->getElementsByTagName('image')->item(0)->nodeValue = $imageFileName;

                    $dom->save($productsXml);
                    
                    $productSubject = new ProductSubject();
                    
                    $productInventoryObserver = new ProductInventoryObserver($productSubject);
                    
                    $productSubject->updateProduct($product_id, $product_title, $product_desc, $product_category, $product_price, $product_stock, $imageFileName);

                    session_start();
                    $_SESSION['edit_success'] = "Product has been updated successfully!";

                    header("Location: index.php?view_product");
                    exit();
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo "<script>alert('Something went wrong. Please try again later.');</script>";
        }
    }
}

function prepareInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<form action="" method="post" enctype="multipart/form-data" class="mb-2">
    <input type="hidden" name="product_id" value="<?php echo isset($product_id) ? $product_id : ''; ?>">
    <input type="hidden" name="product_image_old" value="<?php echo isset($productData['image']) ? $productData['image'] : ''; ?>">

    <div class="mb-3">
        <label class="form-label">Product Title</label>
        <input type="text" name="product_title" class="form-control" value="<?php echo htmlspecialchars($productData['title']); ?>">
        <div class="form-text <?php echo ($productTitleError ? 'text-danger' : ''); ?>">
            <?php if ($productTitleError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productTitleError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Description</label>
        <input type="text" name="product_desc" class="form-control" value="<?php echo htmlspecialchars($productData['desc']); ?>">
        <div class="form-text <?php echo ($productDescError ? 'text-danger' : ''); ?>">
            <?php echo $productDescError; ?><?php if ($productDescError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productDescError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="product_category" class="form-select">
            <?php foreach ($categoriesList as $catTitle): ?>
                <option value="<?php echo htmlspecialchars($catTitle); ?>" 
                        <?php echo $catTitle === $productData['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($catTitle); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div class="form-text <?php echo ($productCategoryError ? 'text-danger' : ''); ?>">
            <?php if ($productCategoryError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productCategoryError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <div class="d-flex flex-column">
            <img id="preview_1" src="uploads/<?php echo htmlspecialchars($productData['image']); ?>" 
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
        <input type="text" name="product_price" class="form-control" value="<?php echo htmlspecialchars($productData['price']); ?>">
        <div class="form-text <?php echo ($productPriceError ? 'text-danger' : ''); ?>">
            <?php if ($productPriceError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productPriceError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Available Stock</label>
        <input type="text" name="product_stock" class="form-control" value="<?php echo htmlspecialchars($productData['stock']); ?>">
        <div class="form-text m-1 mb-3 <?php echo ($productStockError ? 'text-danger' : ''); ?>">
            <?php if ($productStockError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productStockError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="input-group mb-2">
        <input type="submit" class="form-control btn btn-secondary" name="edit_product" value="Update">
    </div>


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
