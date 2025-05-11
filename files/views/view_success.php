<!-- Author     : Christopher Yap Jian Xing -->

<?php
// File paths
$cartXmlPath = '../../xml-files/carts.xml';
$productsXmlPath = '../../xml-files/products.xml';

if (file_exists($cartXmlPath) && file_exists($productsXmlPath)) {
    $cartXml = simplexml_load_file($cartXmlPath);
    $productsXml = simplexml_load_file($productsXmlPath);

    $userEmail = (string) $cartXml->cart->userEmail;
    $totalAmount = 0;
    $cartItems = [];

    foreach ($cartXml->cart->products->product as $cartProduct) {
        $productId = (int) $cartProduct->productId;
        $quantity = (int) $cartProduct->productQty;

        foreach ($productsXml->product as $product) {
            if ((int) $product->id === $productId) {
                $price = (float) $product->price;
                $totalAmount += $price * $quantity;

                $cartItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity
                ];
                break;
            }
        }
    }

    // Insert into database
    $mysqli = new mysqli("localhost", "root", "", "cart");
    if (!$mysqli->connect_error) {
        $stmt = $mysqli->prepare("INSERT INTO cart_history (user_email, total_amount, payment_status) VALUES (?, ?, 'completed')");
        $stmt->bind_param("sd", $userEmail, $totalAmount);
        $stmt->execute();
        $cartId = $stmt->insert_id;
        $stmt->close();

        // Clear the cart XML
        file_put_contents($cartXmlPath, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<carts></carts>");
    } else {
        echo "Database error: " . $mysqli->connect_error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0fff0;
            text-align: center;
            padding: 50px;
        }

        .success-box {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }

        h1 {
            color: #28a745;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        a:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="success-box">
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase. Your transaction has been completed successfully.</p>
        <a href="../userIndex.php">Return to Homepage</a>
    </div>

</body>
</html>
