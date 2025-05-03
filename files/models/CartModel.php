<?php

class CartModel {

    private $xmlFile;

    public function __construct() {
        $this->xmlFile = '../xml-files/carts.xml';
    }

    public function addToCart($user_email, $product_id) {
        if (file_exists($this->xmlFile)) {
            $dom = new DOMDocument();
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->load($this->xmlFile);
            $root = $dom->documentElement;
        } else {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $root = $dom->createElement('carts');
            $dom->appendChild($root);
        }

        $cartFound = false;

        foreach ($root->getElementsByTagName('cart') as $cart) {
            $userEmail = $cart->getElementsByTagName('userEmail')->item(0)->nodeValue;
            if ($userEmail == $user_email) {
                $cartFound = true;

                $productsNode = $cart->getElementsByTagName('products')->item(0);
                $products = $cart->getElementsByTagName('product');
                $productExists = false;

                foreach ($products as $product) {
                    $current_product_id = $product->getElementsByTagName('productId')->item(0)->nodeValue;
                    if ($current_product_id == $product_id) {
                        $qtyNode = $product->getElementsByTagName('productQty')->item(0);
                        $qtyNode->nodeValue = (int) $qtyNode->nodeValue + 1;
                        $productExists = true;
                        break;
                    }
                }

                if (!$productExists) {
                    $newProduct = $dom->createElement('product');
                    $productIdElement = $dom->createElement('productId', $product_id);
                    $productQtyElement = $dom->createElement('productQty', 1);
                    $newProduct->appendChild($productIdElement);
                    $newProduct->appendChild($productQtyElement);
                    $productsNode->appendChild($newProduct);
                }

                break;
            }
        }

        if (!$cartFound) {
            $newCart = $dom->createElement('cart');
            $userEmailElement = $dom->createElement('userEmail', $user_email);
            $products = $dom->createElement('products');

            $newProduct = $dom->createElement('product');
            $productIdElement = $dom->createElement('productId', $product_id);
            $productQtyElement = $dom->createElement('productQty', 1);
            $newProduct->appendChild($productIdElement);
            $newProduct->appendChild($productQtyElement);

            $products->appendChild($newProduct);

            $newCart->appendChild($userEmailElement);
            $newCart->appendChild($products);
            $root->appendChild($newCart);
        }

        $dom->save($this->xmlFile);
    }

    public function getUserCart($user_email) {
        $cartProducts = [];

        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $carts = $dom->getElementsByTagName('cart');

        foreach ($carts as $cart) {
            $current_user_email = $cart->getElementsByTagName('userEmail')->item(0)->nodeValue;
            if ($current_user_email == $user_email) {
                $products = $cart->getElementsByTagName('product');
                foreach ($products as $product) {
                    $productId = $product->getElementsByTagName('productId')->item(0)->nodeValue;
                    $productQty = $product->getElementsByTagName('productQty')->item(0)->nodeValue;
                    $cartProducts[$productId] = $productQty;
                }
                break;
            }
        }

        return $cartProducts;
    }

    public function removeFromCart($user_email, $product_id) {
        $dom = new DOMDocument();
        $dom->load($this->xmlFile);
        $carts = $dom->getElementsByTagName('cart');

        foreach ($carts as $cart) {
            $current_user_email = $cart->getElementsByTagName('userEmail')->item(0)->nodeValue;
            if ($current_user_email == $user_email) {
                $productsNode = $cart->getElementsByTagName('products')->item(0);
                $products = $productsNode->getElementsByTagName('product');

                foreach ($products as $product) {
                    $current_product_id = $product->getElementsByTagName('productId')->item(0)->nodeValue;
                    if ($current_product_id == $product_id) {
                        $productsNode->removeChild($product);
                        break;
                    }
                }
                break;
            }
        }

        $dom->save($this->xmlFile);
    }
}
