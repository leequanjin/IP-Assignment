<?php

/*
 * @author leeda
 */
require_once 'models/CartModel.php';
require_once 'models/ProductModel.php';

class CartController {

    private $model;

    public function __construct() {
        $this->model = new CartModel();
    }

    public function handleRequest($action) {

        switch ($action) {
            case 'add':
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["add_to_cart"])) {
                    $user_id = 1;
                    $product_id = $_GET["add_to_cart"];
                    $this->model->addToCart($user_id, $product_id);
                }
            default:
        }
        
        header("Location: userIndex.php");
    }
}
