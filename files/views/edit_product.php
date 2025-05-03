<?php
require_once 'controllers/ProductController.php';

$product_id = $_POST['product_id'] ?? $productData['id'] ?? '';
$product_title = $_POST['product_title'] ?? $productData['title'] ?? '';
$product_desc = $_POST['product_desc'] ?? $productData['desc'] ?? '';
$product_category = $_POST['product_category'] ?? $productData['category'] ?? '';
$product_price = $_POST['product_price'] ?? $productData['price'] ?? '';
$product_stock = $_POST['product_stock'] ?? $productData['stock'] ?? '';
$product_image = $productData['image'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_image = 'no-image.jpg';
}
?>

<form action="" method="post" enctype="multipart/form-data" class="mb-2">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
    <input type="hidden" name="product_image_old" value="<?php echo htmlspecialchars($product_image_old); ?>">

    <div class="mb-3">
        <label class="form-label">Product Title</label>
        <input type="text" name="product_title" class="form-control"
               value="<?php echo htmlspecialchars($product_title); ?>">
        <div class="form-text <?php echo ($productTitleError ? 'text-danger' : ''); ?>">
            <?php if ($productTitleError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productTitleError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Description</label>
        <input type="text" name="product_desc" class="form-control"
               value="<?php echo htmlspecialchars($product_desc); ?>">
        <div class="form-text <?php echo ($productDescError ? 'text-danger' : ''); ?>">
            <?php if ($productDescError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productDescError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="product_category" class="form-select">
            <?php foreach ($categories as $category): ?>
                <?php $category_title = $category->getElementsByTagName('title')->item(0)->nodeValue; ?>
                <option value="<?php echo htmlspecialchars($category_title); ?>"
                        <?php echo ($product_category === $category_title ? 'selected' : ''); ?>>
                            <?php echo htmlspecialchars($category_title); ?>
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
            <img id="preview_1" src="uploads/<?php echo htmlspecialchars($product_image); ?>"
                 alt="Product Image" width="150" height="150"
                 class="mb-2 rounded shadow-sm" style="object-fit: cover;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('product_image').click();">
                Choose Image
            </button>
            <input type="file" name="product_image" id="product_image" style="display: none;"
                   onchange="previewImage(event, 1);">
        </div>
        <div class="form-text m-1 mb-3 <?php echo ($productImageError ? 'text-danger' : ''); ?>">
            <?php if ($productImageError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productImageError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Price (RM)</label>
        <input type="text" name="product_price" class="form-control"
               value="<?php echo htmlspecialchars($product_price); ?>">
        <div class="form-text <?php echo ($productPriceError ? 'text-danger' : ''); ?>">
            <?php if ($productPriceError): ?>
                <i class="fa-solid fa-circle-exclamation"></i> <?php echo $productPriceError; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Available Stock</label>
        <input type="text" name="product_stock" class="form-control"
               value="<?php echo htmlspecialchars($product_stock); ?>">
        <div class="form-text <?php echo ($productStockError ? 'text-danger' : ''); ?>">
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
