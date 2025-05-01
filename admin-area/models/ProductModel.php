<?php

require_once '../product-observer/Subject.php';
require_once '../product-observer/ProductSubject.php';
require_once '../product-observer/Observer.php';
require_once '../product-observer/ProductInventoryObserver.php';
require_once '../product-observer/ProductLogObserver.php';
require_once '../product-observer/ProductPriceChangeObserver.php';

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

    public function insertProduct($product_title, $product_desc, $product_category, $product_image, $product_price, $product_stock) {
        $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';
        $successMessage = '';

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
            $fileExtension = strtolower(pathinfo($product_image['name'], PATHINFO_EXTENSION));
            if ($fileExtension !== 'png') {
                $productImageError = "Only PNG extension files are allowed.";
                $hasError = true;
            }

            $mimeType = mime_content_type($product_image['tmp_name']);
            if ($mimeType !== 'image/png') {
                $productImageError = "Only PNG MIME type files are allowed.";
                $hasError = true;
            }

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
                $dom->save($this->xmlFile);

                $successMessage = "Product has been inserted successfully!";
            }
        }

        return [$productTitleError, $productDescError, $productCategoryError, $productImageError, $productPriceError, $productStockError, $successMessage];
    }

    public function getProductData($idToEdit) {
        $productData = [
            'title' => '',
            'desc' => '',
            'category' => '',
            'price' => '',
            'stock' => '',
            'image' => ''
        ];

        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $products = $dom->getElementsByTagName('product');

        foreach ($products as $product) {
            $id = $product->getElementsByTagName('id')->item(0)->nodeValue;
            if ($id === $idToEdit) {
                $productData['title'] = $product->getElementsByTagName('title')->item(0)->nodeValue;
                $productData['desc'] = $product->getElementsByTagName('description')->item(0)->nodeValue;
                $productData['category'] = $product->getElementsByTagName('category')->item(0)->nodeValue;
                $productData['price'] = $product->getElementsByTagName('price')->item(0)->nodeValue;
                $productData['stock'] = $product->getElementsByTagName('stock')->item(0)->nodeValue;
                $productData['image'] = $product->getElementsByTagName('image')->item(0)->nodeValue;
                break;
            }
        }
        return $productData;
    }

    public function editProduct($idToEdit, $product_title, $product_desc, $product_category, $product_image, $product_price, $product_stock) {
        $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';

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
            $fileExtension = strtolower(pathinfo($product_image['name'], PATHINFO_EXTENSION));
            if ($fileExtension !== 'png') {
                $productImageError = "Only PNG files are allowed.";
                $hasError = true;
            }

            $mimeType = mime_content_type($product_image['tmp_name']);
            if ($mimeType !== 'image/png') {
                $productImageError = "Only PNG files are allowed.";
                $hasError = true;
            }

            $imageInfo = getimagesize($product_image['tmp_name']);
            if ($imageInfo === false) {
                $productImageError = "Uploaded file is not a valid image.";
                $hasError = true;
            }
        }

        $imageFileName = '';

        if (!$hasError) {
            $productData = $this->getProductData($idToEdit);
            $product_image_old = $productData['image'];
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

                        $product->getElementsByTagName('title')->item(0)->nodeValue = $product_title;
                        $product->getElementsByTagName('description')->item(0)->nodeValue = $product_desc;
                        $product->getElementsByTagName('category')->item(0)->nodeValue = $product_category;
                        $product->getElementsByTagName('price')->item(0)->nodeValue = $product_price;
                        $product->getElementsByTagName('stock')->item(0)->nodeValue = $product_stock;
                        $product->getElementsByTagName('image')->item(0)->nodeValue = $imageFileName;

                        $dom->save($this->xmlFile);

                        $productSubject = new ProductSubject();

                        $productInventoryObserver = new ProductInventoryObserver($productSubject);
                        $ProductPriceChangeObserver = new ProductPriceChangeObserver($productSubject);
                        $ProductLogObserver = new ProductLogObserver($productSubject);

                        $productSubject->updateProduct(
                                $product_id,
                                $oldTitle, $oldDescription, $oldCategory, $oldPrice, $oldStock, $oldImage,
                                $product_title, $product_desc, $product_category, $product_price, $product_stock, $imageFileName
                        );
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
                $product->parentNode->removeChild($product);
                $dom->save($this->xmlFile);
                return true;
            }
        }
        return false;
    }
}
