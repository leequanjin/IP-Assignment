<?php

/*
 * @author leeda
 */
require_once 'models/CategoryModel.php';
require_once 'models/ProductModel.php';
require_once 'apis/CurrencyConverter.php';

class UserIndexController {

    public function index() {
        $categoryModel = new CategoryModel();
        $productModel = new ProductModel();

        $search = $_GET['search'] ?? null;
        $selectedCategory = $_GET['category'] ?? null;

        $categories = $categoryModel->getAllCategories();
        $products = $productModel->getProducts($search, $selectedCategory);

        include 'views/user_index_view.php';
    }
}
