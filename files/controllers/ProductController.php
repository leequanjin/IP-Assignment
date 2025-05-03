<?php

require_once 'models/ProductModel.php';
require_once 'models/CategoryModel.php';

class ProductController {

    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function handleRequest($action) {

        switch ($action) {
            case 'insert':
                $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';
                $successMessage = '';

                $categoryModel = new CategoryModel();
                $categories = $categoryModel->getAllCategories();

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_product"])) {
                    $product_title = prepareInput($_POST["product_title"]);
                    $product_desc = prepareInput($_POST["product_desc"]);
                    $product_category = prepareInput($_POST["product_category"]);
                    $product_image = $_FILES["product_image"];
                    $product_price = prepareInput($_POST["product_price"]);
                    $product_stock = prepareInput($_POST["product_stock"]);

                    list($productTitleError, $productDescError, $productCategoryError, $productImageError, $productPriceError, $productStockError, $successMessage) = $this->model->insertProduct($product_title, $product_desc, $product_category, $product_image, $product_price, $product_stock);
                }

                include 'views/insert_product.php';
                break;

            case 'view':
                $xml = new DOMDocument();
                $xml->load('../xml-files/products.xml');

                $xsl = new DOMDocument();
                $xsl->load('xslt/staff_product_view.xsl');

                $proc = new XSLTProcessor();
                $proc->importStylesheet($xsl);
                $productTableHtml = $proc->transformToXml($xml);

                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_product"])) {
                    $idToDelete = $_GET["delete_product"];
                    if ($this->model->deleteProduct($idToDelete)) {
                        header("Location: adminIndex.php?module=product&action=view");
                        exit;
                    }
                }

                include 'views/view_product.php';
                break;

            case 'edit':
                $productTitleError = $productDescError = $productCategoryError = $productImageError = $productPriceError = $productStockError = '';
                $idToEdit = '';
                $productData = [
                    'title' => '',
                    'desc' => '',
                    'category' => '',
                    'price' => '',
                    'stock' => '',
                    'image' => ''
                ];

                $categoryModel = new CategoryModel();
                $categories = $categoryModel->getAllCategories();

                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["edit_product"])) {
                    $idToEdit = $_GET["edit_product"];
                    $productData = $this->model->getProductData($idToEdit);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_product"])) {
                    $idToEdit = $_GET["edit_product"];
                    $product_title = prepareInput($_POST["product_title"]);
                    $product_desc = prepareInput($_POST["product_desc"]);
                    $product_category = prepareInput($_POST["product_category"]);
                    $product_image = $_FILES["product_image"];
                    $product_price = prepareInput($_POST["product_price"]);
                    $product_stock = prepareInput($_POST["product_stock"]);

                    list($productTitleError,
                            $productDescError,
                            $productCategoryError,
                            $productImageError,
                            $productPriceError,
                            $productStockError) = $this->model->editProduct($idToEdit, $product_title, $product_desc, $product_category, $product_image, $product_price, $product_stock);

                    if (
                            empty($productTitleError) &&
                            empty($productDescError) &&
                            empty($productCategoryError) &&
                            empty($productImageError) &&
                            empty($productPriceError) &&
                            empty($productStockError)
                    ) {
                        header("Location: adminIndex.php?module=product&action=view");
                        exit;
                    }
                }

                include 'views/edit_product.php';
                break;

            default:
        }
    }
}

function prepareInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
