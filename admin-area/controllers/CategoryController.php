<?php
require_once '../model/CategoryModel.php';

class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? null;

        try {
            switch ($action) {
                case 'insert':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $this->model->insertCategory($_POST['category_title']);
                        $_SESSION['success_message'] = "Category inserted.";
                        header("Location: index.php?action=view");
                        exit();
                    }
                    include '../view/insert_category.php';
                    break;
                case 'delete':
                    if (isset($_GET['title'])) {
                        $this->model->deleteCategory($_GET['title']);
                        $_SESSION['success_message'] = "Category deleted.";
                    }
                    header("Location: index.php?action=view");
                    exit();
                case 'view':
                default:
                    $categories = $this->model->getAllCategories();
                    include '../view/view_category.php';
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            include '../view/error.php';
        }
    }
}
