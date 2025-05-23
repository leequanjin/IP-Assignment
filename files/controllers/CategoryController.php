<!-- Author     : Lee Quan Jin -->

<?php

require_once 'models/CategoryModel.php';

class CategoryController {

    private $model;

    public function __construct() {
        $this->model = new CategoryModel();
    }

    public function handleRequest($action) {

        switch ($action) {
            case 'insert':
                $error = '';
                $successMessage = '';

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_category"])) {
                    $category_title = prepareInput($_POST["category_title"]);
                    list($error, $successMessage) = $this->model->insertCategory($category_title);
                }

                include 'views/insert_category.php';
                break;

            case 'view':
                $xml = new DOMDocument();
                $xml->load('../xml-files/categories.xml');

                $xsl = new DOMDocument();
                $xsl->load('xslt/category_view.xsl');

                $proc = new XSLTProcessor();
                $proc->importStylesheet($xsl);
                $categoryTable = $proc->transformToXml($xml);

                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_category"])) {
                    $titleToDelete = $_GET["delete_category"];
                    if ($this->model->deleteCategory($titleToDelete)) {
                        header("Location: adminIndex.php?module=category&action=view");
                        exit;
                    }
                }
                include 'views/view_category.php';
                break;

            default:
        }
    }
}

function prepareInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
