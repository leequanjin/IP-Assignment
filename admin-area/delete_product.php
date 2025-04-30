<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete_product"])) {
    $productId = (int) $_GET["delete_product"];

    $xmlFile = '../xml-files/products.xml';

    $dom = new DOMDocument();
    $dom->load($xmlFile);

    $products = $dom->getElementsByTagName('product');

    foreach ($products as $product) {
        $id = $product->getElementsByTagName('id')->item(0)->nodeValue;

        if ($id == $productId) {
            $image = $product->getElementsByTagName('image')->item(0)->nodeValue;
            unlink('uploads/' . $image);
            $product->parentNode->removeChild($product);
            $dom->save($xmlFile);
            $_SESSION['success_message'] = "Product deleted successfully!";
            break;
        }
    }

    header("Location: index.php?view_product");
    exit();
}

