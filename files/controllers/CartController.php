<?php

/*
 * @author leeda
 */
require_once 'models/CartModel.php';
require_once 'models/ProductModel.php';
require_once 'apis/CurrencyConverter.php';

class CartController {

    private $model;

    public function __construct() {
        $this->model = new CartModel();
    }

    public function handleRequest($action) {
        $currencyParam = isset($_GET['currency']) ? '&currency=' . urlencode($_GET['currency']) : '';

        switch ($action) {
            
            case 'add':
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["add_to_cart"])) {
                    $user_email = $_SESSION['email'];
                    $product_id = $_GET["add_to_cart"];
                    $this->model->addToCart($user_email, $product_id);
                }
                
                header("Location: userIndex.php?$currencyParam");
                exit;
                break;

            case 'view':
                $user_email = $_SESSION['email'];
                $cartProducts = $this->model->getUserCart($user_email);

                $productModel = new ProductModel();
                $cartProductDetails = [];

                foreach ($cartProducts as $productId => $qty) {
                    $productData = $productModel->getProductData($productId);
                    if (!empty($productData['title'])) {
                        $productData['qty'] = $qty;
                        $cartProductDetails[] = $productData;
                    }
                }
                include 'views/view_cart.php';
                break;

            case 'delete':
                $user_email = $_SESSION['email'];
                if (isset($_GET['product_id'])) {
                    $product_id = $_GET['product_id'];
                    $this->model->removeFromCart($user_email, $product_id);
                }
                header("Location: userIndex.php?module=cart&action=view$currencyParam");
                exit;
                break;
                
            default:
        }
    }
}
