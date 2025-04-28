<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_category"])) {
    $catTitle = $_GET["delete_category"];

    $xmlFile = '../xml-files/categories.xml';

    $dom = new DOMDocument();
    $dom->load($xmlFile);

    $categories = $dom->getElementsByTagName('category');

    foreach ($categories as $category) {
        $title = $category->getElementsByTagName('title')->item(0)->nodeValue;

        if ($title == $catTitle) {
            $category->parentNode->removeChild($category);
            $dom->save($xmlFile);
            $_SESSION['success_message'] = "Category deleted successfully!";
            break;
        }
    }

    header("Location: index.php?view_category");
    exit();
}

