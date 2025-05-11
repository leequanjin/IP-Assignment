<!-- Author     : Christopher Yap Jian Xing -->

<?php

require_once 'models/CartModel.php';
require_once 'models/ProductModel.php';
require_once 'apis/CurrencyConverter.php';
require_once 'decorator/BasePrice.php';
require_once 'decorator/DiscountDecorator.php';

class CartController {

    private $model;

    public function __construct() {
        $this->model = new CartModel();
    }

    public function handleRequest($action) {
        $categoryParam = isset($_GET['category']) ? '&category=' . urlencode($_GET['category']) : '';
        $currencyParam = isset($_GET['currency']) ? '&currency=' . urlencode($_GET['currency']) : '';

        switch ($action) {

            case 'add':
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["add_to_cart"])) {
                    $user_email = $_SESSION['email'];
                    $product_id = $_GET["add_to_cart"];
                    $this->model->addToCart($user_email, $product_id);

                    echo "<script>window.location.href='userIndex.php?$categoryParam$currencyParam';</script>";
                    exit;
                }

            case 'view':
                $user_email = $_SESSION['email'] ?? '';
                $cartProducts = $this->model->getUserCart($user_email);

                $productModel = new ProductModel();
                $cartProductDetails = [];

                foreach ($cartProducts as $productId => $qty) {
                    $productData = $productModel->getProductData($productId);
                    if (!empty($productData->title)) {
                        $productData->qty = $qty;
                        $cartProductDetails[] = $productData;
                    }
                }
                include 'views/view_cart.php';
                break;

            case 'viewCart':

                // Define relative paths
                $xmlPath = '../xml-files/carts.xml'; // Going up one directory from views to xml-files
                $xslPath = 'xslt/cart_report.xsl'; // Going up one directory from views to xslt
                // Create new DOMDocument instance and load XML
                $xml = new DOMDocument();
                if (!$xml->load($xmlPath)) {
                    echo "Failed to load XML file.";
                    exit;
                }

                // Load XSLT
                $xsl = new DOMDocument();
                if (!$xsl->load($xslPath)) {
                    echo "Failed to load XSL file.";
                    exit;
                }

                // Configure XSLT processor
                $proc = new XSLTProcessor();
                $proc->importStyleSheet($xsl); // Attach the XSL stylesheet
                // Output transformed HTML
                $transform = $proc->transformToXML($xml);
                
                include 'views/view_cart_report.php';
                break;

            case 'delete':
                $user_email = $_SESSION['email'];
                if (isset($_GET['product_id'])) {
                    $product_id = $_GET['product_id'];
                    $this->model->removeFromCart($user_email, $product_id);
                }
                echo "<script>window.location.href='userIndex.php?module=cart&action=view$currencyParam';</script>";
                exit;
                break;

            case 'update':
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'], $_POST['quantity'])) {
                    $user_email = $_SESSION['email'];
                    $product_id = (int) $_POST['product_id'];
                    $quantity = max(1, (int) $_POST['quantity']); // at least 1
                    $this->model->updateCartQuantity($user_email, $product_id, $quantity);
                }
                echo "<script>window.location.href='userIndex.php?module=cart&action=view$currencyParam';</script>";
                exit;
                break;

            default:
        }
    }

}
