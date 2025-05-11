<!-- Author     : Lee Quan Jin -->

<?php
require_once '../product-observer/Subject.php';
require_once '../product-observer/ProductSubject.php';
require_once '../product-observer/Observer.php';
require_once '../product-observer/ProductInventoryObserver.php';
require_once '../product-observer/ProductLogObserver.php';
require_once '../product-observer/ProductPriceChangeObserver.php';
require_once 'Product.php';

class ProductModel {

    private $xmlFile;

    public function __construct() {
        $this->xmlFile = '../xml-files/products.xml';
    }

    public function getAllProducts() {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        return $dom->getElementsByTagName('product');
    }

    public function insertProduct(Product $newProduct) {
        $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';
        $successMessage = '';

        $hasError = false;

        if (!$newProduct->title) {
            $productTitleError = "Please enter a product title.";
            $hasError = true;
        }
        if (!$newProduct->description) {
            $productDescError = "Please enter a product description.";
            $hasError = true;
        }
        if (!$newProduct->category || $newProduct->category == "Choose...") {
            $productCategoryError = "Please select a category.";
            $hasError = true;
        }
        if (!$newProduct->price) {
            $productPriceError = "Please enter a product price.";
            $hasError = true;
        } elseif (!is_numeric($newProduct->price) || $newProduct->price < 0) {
            $productPriceError = "Please enter a valid positive number.";
            $hasError = true;
        }
        if (!$newProduct->stock) {
            $productStockError = "Please enter available stock.";
            $hasError = true;
        }
        if (!$newProduct->image || $newProduct->image['error'] !== UPLOAD_ERR_OK) {
            $productImageError = "Please upload a product image.";
            $hasError = true;
        } else {
            $fileExtension = strtolower(pathinfo($newProduct->image['name'], PATHINFO_EXTENSION));
            if ($fileExtension !== 'png') {
                $productImageError = "Only PNG extension files are allowed.";
                $hasError = true;
            }

            $mimeType = mime_content_type($newProduct->image['tmp_name']);
            if ($mimeType !== 'image/png') {
                $productImageError = "Only PNG MIME type files are allowed.";
                $hasError = true;
            }

            $imageInfo = getimagesize($newProduct->image['tmp_name']);
            if ($imageInfo === false) {
                $productImageError = "Uploaded file is not a valid image.";
                $hasError = true;
            }
        }

        if (!$hasError) {
            $imageFileName = '';
            $newFileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $fileExtension;
            $uploadPath = 'uploads/' . $newFileName;

            if (!move_uploaded_file($newProduct->image['tmp_name'], $uploadPath)) {
                $productImageError = "Error uploading the image.";
                $hasError = true;
            } else {
                $imageFileName = $newFileName;
            }

            if (!$hasError) {
                if (file_exists($this->xmlFile)) {
                    $dom = new DOMDocument();
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->load($this->xmlFile);
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

                $newProductElement = $dom->createElement('product');

                $idElement = $dom->createElement('id', $product_id);
                $titleElement = $dom->createElement('title', $newProduct->title);
                $descElement = $dom->createElement('description', $newProduct->description);
                $categoryElement = $dom->createElement('category', $newProduct->category);
                $priceElement = $dom->createElement('price', $newProduct->price);
                $stockElement = $dom->createElement('stock', $newProduct->stock);
                $imageElement = $dom->createElement('image', $imageFileName);

                $newProductElement->appendChild($idElement);
                $newProductElement->appendChild($titleElement);
                $newProductElement->appendChild($descElement);
                $newProductElement->appendChild($categoryElement);
                $newProductElement->appendChild($priceElement);
                $newProductElement->appendChild($stockElement);
                $newProductElement->appendChild($imageElement);

                $root->appendChild($newProductElement);
                $dom->save($this->xmlFile);

                $successMessage = "Product has been inserted successfully!";
            }
        }

        return [$productTitleError, $productDescError, $productCategoryError, $productImageError, $productPriceError, $productStockError, $successMessage];
    }

    public function getProductData($id) {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $products = $dom->getElementsByTagName('product');

        foreach ($products as $product) {
            $current_id = $product->getElementsByTagName('id')->item(0)->nodeValue;
            if ($current_id == $id) {
                $title = $product->getElementsByTagName('title')->item(0)->nodeValue;
                $description = $product->getElementsByTagName('description')->item(0)->nodeValue;
                $category = $product->getElementsByTagName('category')->item(0)->nodeValue;
                $price = $product->getElementsByTagName('price')->item(0)->nodeValue;
                $stock = $product->getElementsByTagName('stock')->item(0)->nodeValue;
                $image = $product->getElementsByTagName('image')->item(0)->nodeValue;

                return new Product($id, $title, $description, $category, $price, $stock, $image);
            }
        }

        return null;
    }

    public function editProduct(Product $editedProduct, $idToEdit) {
        $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';

        $hasError = false;

        if (empty($editedProduct->title)) {
            $productTitleError = "Please enter a product title.";
            $hasError = true;
        }
        if (empty($editedProduct->description)) {
            $productDescError = "Please enter a product description.";
            $hasError = true;
        }
        if (empty($editedProduct->category) || $editedProduct->category == "Choose...") {
            $productCategoryError = "Please select a category.";
            $hasError = true;
        }
        if (empty($editedProduct->price)) {
            $productPriceError = "Please enter a product price.";
            $hasError = true;
        } elseif (!is_numeric($editedProduct->price) || $editedProduct->price < 0) {
            $productPriceError = "Please enter a valid positive number.";
            $hasError = true;
        }
        if (!$editedProduct->stock) {
            $productStockError = "Please enter available stock.";
            $hasError = true;
        }
        if ($editedProduct->image && $editedProduct->image['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($editedProduct->image['name'], PATHINFO_EXTENSION));
            if ($fileExtension !== 'png') {
                $productImageError = "Only PNG files are allowed.";
                $hasError = true;
            }

            $mimeType = mime_content_type($editedProduct->image['tmp_name']);
            if ($mimeType !== 'image/png') {
                $productImageError = "Only PNG files are allowed.";
                $hasError = true;
            }

            $imageInfo = getimagesize($editedProduct->image['tmp_name']);
            if ($imageInfo === false) {
                $productImageError = "Uploaded file is not a valid image.";
                $hasError = true;
            }
        }

        $imageFileName = '';

        if (!$hasError) {
            $productData = $this->getProductData($idToEdit);
            $product_image_old = $productData->image;
            if ($editedProduct->image && $editedProduct->image['error'] === UPLOAD_ERR_OK) {
                $newFileName = time() . "_" . bin2hex(random_bytes(5)) . '.' . $fileExtension;
                $uploadPath = 'uploads/' . $newFileName;

                if (!move_uploaded_file($editedProduct->image['tmp_name'], $uploadPath)) {
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
                $dom->load($this->xmlFile);
                $products = $dom->getElementsByTagName('product');

                foreach ($products as $product) {
                    $product_id = $product->getElementsByTagName('id')->item(0)->nodeValue;
                    if ($product_id === $idToEdit) {
                        $oldTitle = $product->getElementsByTagName('title')->item(0)->nodeValue;
                        $oldDescription = $product->getElementsByTagName('description')->item(0)->nodeValue;
                        $oldCategory = $product->getElementsByTagName('category')->item(0)->nodeValue;
                        $oldPrice = $product->getElementsByTagName('price')->item(0)->nodeValue;
                        $oldStock = $product->getElementsByTagName('stock')->item(0)->nodeValue;
                        $oldImage = $product->getElementsByTagName('image')->item(0)->nodeValue;

                        $product->getElementsByTagName('title')->item(0)->nodeValue = $editedProduct->title;
                        $product->getElementsByTagName('description')->item(0)->nodeValue = $editedProduct->description;
                        $product->getElementsByTagName('category')->item(0)->nodeValue = $editedProduct->category;
                        $product->getElementsByTagName('price')->item(0)->nodeValue = $editedProduct->price;
                        $product->getElementsByTagName('stock')->item(0)->nodeValue = $editedProduct->stock;
                        $product->getElementsByTagName('image')->item(0)->nodeValue = $imageFileName;

                        $dom->save($this->xmlFile);

                        $productSubject = new ProductSubject();

                        $productInventoryObserver = new ProductInventoryObserver($productSubject);
                        $ProductPriceChangeObserver = new ProductPriceChangeObserver($productSubject);
                        $ProductLogObserver = new ProductLogObserver($productSubject);

                        $oldProduct = new Product($product_id, $oldTitle, $oldDescription, $oldCategory, $oldPrice, $oldStock, $oldImage);

                        $productSubject->updateProduct($oldProduct, $editedProduct);
                    }
                }
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo "<script>alert('Something went wrong. Please try again later.');</script>";
            }
        }

        return [$productTitleError,
            $productDescError,
            $productCategoryError,
            $productImageError,
            $productPriceError,
            $productStockError];
    }

    public function deleteProduct($idToDelete) {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $products = $dom->getElementsByTagName('product');

        foreach ($products as $product) {
            $id = $product->getElementsByTagName('id')->item(0)->nodeValue;
            if ($id === $idToDelete) {
                $image = $product->getElementsByTagName('image')->item(0)->nodeValue;
                unlink('uploads/' . $image);
                $product->parentNode->removeChild($product);
                $dom->save($this->xmlFile);
                return true;
            }
        }
        return false;
    }

    public function getProducts($search = null, $filterCategory = null) {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $products = $dom->getElementsByTagName('product');

        $filtered = [];

        foreach ($products as $product) {
            $title = $product->getElementsByTagName('title')->item(0)->nodeValue;
            $category = $product->getElementsByTagName('category')->item(0)->nodeValue;

            if ($filterCategory && $filterCategory !== $category)
                continue;
            if ($search && stripos($title, $search) === false)
                continue;

            $filtered[] = $product;
        }

        return $filtered;
    }
}
